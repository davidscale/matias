<?php 

namespace backend\models\db_guarani;


use Yii;
use yii\base\Model;

use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaPeriodos;
use backend\models\db_guarani\SgaComisiones;
use backend\models\db_guarani\SgaActas;
use backend\models\db_guarani\SgaActasDetalle;

// Lib. de PhpOffice
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PHPOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

use kartik\spinner\Spinner;


class Reportes_Form extends Model{

    private $rep;

    // Variables para los reportes
    public $tipo_reporte;    
    public $propuesta;    
    public $anio;
    public $ubicacion;
    public $periodo;
    public $cuatrimestre;
    public $elements;   //filter elements at the moment to report

    // Variables para el excel
    private $title = '';
    private $subtitle = '';
    private $name_arc = '';

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            ['elements', 'default'],
            ['cuatrimestre', 'required'],
            ['tipo_reporte', 'required'],
            //1er reporte
            [['propuesta', 'anio', 'ubicacion', 'periodo'], 'required',
                'when' => function ($model) {
                    return $model->tipo_reporte == 'notas_cursadas';
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#tipo_reporte').val() == 'notas_cursadas';
                                }"
            ],
            //2do reporte
            [['anio', 'periodo'], 'required',
                'when' => function ($model) {
                    return $model->tipo_reporte == 'rend_catedras';
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#tipo_reporte').val() == 'rend_catedras';
                                }"],
            //3er reporte
            [['propuesta', 'anio', 'ubicacion', 'periodo'], 'required',
                'when' => function ($model) {
                    return $model->tipo_reporte == 'ins_cursada';
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#tipo_reporte').val() == 'ins_cursada';
                                }"],

        ];
    }

    // Seleccion de query 
    private function getQuery()
    {
        $rta = null;
        $filter_elements_command = "";

        if ($this->elements) {
            foreach ($this->elements as $e) {
                $filter_elements_command .= "AND ele.codigo != '" . $e . "' ";
            }
        }

        switch ($this->tipo_reporte ) {
            case 'notas_cursadas':
                $rta = "SELECT 
                            per.apellido,
                            per.nombres,
                            pd.nro_documento,
                            co.nombre AS comision,
                            ele.codigo AS code_subject,
                            ad.cond_regularidad,
                            
                            CASE
                                WHEN (ad.resultado = 'A') THEN 'P'
                                WHEN (ad.resultado = 'R') THEN 'N'
                                WHEN (ad.resultado = 'U') THEN 'U'
                                END AS resultado,
                            CASE 
                                WHEN (ad.nota = 'Ausente') THEN ''
                                ELSE ad.nota
                                END AS nota,

                            to_char(ad.fecha, 'DD/MM/YYYY') AS fecha,
                            libro.nro_libro,
                            acta.nro_acta,
                            ad.folio,
                            ad.renglon,
                            acta.renglones_folio,
                            acta.origen
                        FROM sga_comisiones AS co

                        JOIN sga_elementos AS ele ON co.elemento = ele.elemento
                        JOIN sga_actas AS acta ON co.comision = acta.comision
                        LEFT JOIN sga_actas_detalle AS ad ON acta.id_acta = ad.id_acta
                        JOIN sga_actas_folios AS af ON acta.id_acta = af.id_acta AND af.folio = ad.folio
                        JOIN sga_libros_tomos AS lt ON af.libro_tomo = lt.libro_tomo
                        JOIN sga_libros_actas AS libro ON lt.libro = libro.libro
                        LEFT JOIN sga_cond_regularidad AS cr ON ad.cond_regularidad = cr.cond_regularidad
                        LEFT JOIN sga_escalas_notas_resultado AS enr ON ad.resultado = enr.resultado
                        JOIN sga_alumnos AS alu ON ad.alumno = alu.alumno
                        JOIN mdp_personas AS per ON alu.persona = per.persona
                        JOIN mdp_personas_documentos AS pd ON alu.persona = pd.persona
                        JOIN sga_periodos_lectivos AS pl ON co.periodo_lectivo = pl.periodo_lectivo
                        JOIN sga_periodos AS ps ON pl.periodo = ps.periodo

                        WHERE co.ubicacion = $this->ubicacion 
                        AND acta.origen = 'P'
                        AND pl.periodo = $this->periodo
                        $filter_elements_command    /*  remove subjects selected by user */

                        ORDER BY 1, 3";
                break;

            case 'rend_catedras':
                $rta = "SELECT 
                            ele.codigo AS materia, 
                            ca.nombre AS catedra, 
                            COUNT (CASE WHEN d.resultado = 'U' THEN 1 ELSE NULL END) AS aprob, 
                            COUNT (CASE WHEN d.resultado = 'A' THEN 1 ELSE NULL END) AS repro, 
                            COUNT (CASE WHEN d.resultado != 'U' AND d.resultado != 'A' THEN 1 ELSE NULL END) AS ausen
                        FROM sga_actas a 

                        LEFT JOIN sga_actas_detalle d ON a.id_acta = d.id_acta
                        LEFT JOIN sga_comisiones co ON a.comision = co.comision
                        LEFT JOIN sga_catedras ca ON co.catedra = ca.catedra
                        LEFT JOIN sga_elementos ele ON co.elemento = ele.elemento

                        JOIN sga_periodos_lectivos AS pl ON co.periodo_lectivo = pl.periodo_lectivo 
                        JOIN sga_periodos AS ps ON pl.periodo = ps.periodo

                        WHERE a.origen = 'P' 
                        AND pl.periodo = $this->periodo
                        $filter_elements_command    /*  remove subjects selected by user */
                        
                        GROUP BY ca.nombre, co.elemento, ele.codigo
                        ORDER BY 1, 2";
                break;

            case 'ins_cursada':
                $rta = "SELECT 
                            com.nombre AS comision,
                            cat.nombre AS catedra,
                            pd.nro_documento,
                            per.apellido,
                            per.nombres,
                            pc.email,
                            per.usuario,
                            ele.nombre AS materia,
                            ele.codigo AS code_subject
                        FROM mdp_personas AS per

                        LEFT JOIN mdp_personas_documentos AS pd ON per.persona = pd.persona
                        LEFT JOIN mdp_personas_contactos AS pc ON per.persona = pc.persona
                        LEFT JOIN sga_alumnos AS alu ON per.persona = alu.persona
                        LEFT JOIN sga_insc_cursada AS insc ON alu.alumno = insc.alumno
                        LEFT JOIN sga_comisiones AS com ON insc.comision = com.comision
                        LEFT JOIN sga_catedras AS cat ON com.catedra = cat.catedra
                        LEFT JOIN sga_elementos AS ele ON com.elemento = ele.elemento
                        JOIN sga_periodos_lectivos AS pl ON com.periodo_lectivo = pl.periodo_lectivo
                        JOIN sga_periodos AS ps ON pl.periodo = ps.periodo

                        WHERE pc.contacto_tipo = 'MP'
                        AND com.ubicacion = $this->ubicacion
                        AND pl.periodo = $this->periodo
                        $filter_elements_command    /*  remove subjects selected by user */

                        ORDER BY 3";
                break;
        }
        return $rta;
    }

    // Retorna un reporte luego de una busqueda de los datos ingresados
    public function generar_reporte()
    {
        ini_set('memory_limit', '-1');
        if ($this->validate()) {
            $query = $this->getQuery();
            $data = Yii::$app->db_guarani->createCommand($query)->queryAll();

            // var_dump($data);
            // var_dump(isset($data));die;

            if ($data && isset($data)) {
                $this->rep = (object) $data;

                //asigno los titulos
                if($this->tipo_reporte == 'notas_cursadas'){
                    $this->title = 'NOTAS DE CURSADA (para académico)';
                    $this->name_arc = 'Notas de Cursada (para académico)';
                }

                if($this->tipo_reporte == 'rend_catedras'){
                    $data = SgaPeriodos::find()->where(['periodo' => $this->periodo])->one();

                    $periodo = strtoupper(utf8_encode($data->nombre));
                    
                    $this->title = 'RENDIMIENTO DE CÁTEDRA - ' . $periodo;
                    $this->subtitle = $periodo;
                    $this->name_arc = 'Rendimiento de Cátedras';
                }

                if($this->tipo_reporte == 'ins_cursada'){

                    $data = SgaPeriodos::find()->where(['periodo' => $this->periodo])->one();
                    $periodo = strtoupper(utf8_encode($data->nombre));

                    $this->title = 'REPORTE DE INSCRIPCIÓN DE CURSADA - ' . $periodo;
                    $this->subtitle = $periodo;
                    $this->name_arc = 'Reporte de inscripción de cursada';
                }

                Yii::$app->session->setFlash('succes', 'okok...');

                return $this->rep;
            } else {
                // var_dump('ghgfhgfgh');die; 


                Yii::$app->session->setFlash('danger', 'sin info...');

                return null;
            }
        } else {
                // var_dump('ghgfhgfgh');die; 

                Yii::$app->session->setFlash('danger', 'sin info...');


                return null;
            }
        // return null;
    }

    // Funcion que genera los excel
    public function generar_reporte_excel(): bool
    {
        // It will take unlimited memory usage of server, it's working fine.
        ini_set('memory_limit', '-1');

        if ($this->generar_reporte()) {

            if($this->tipo_reporte == 'notas_cursadas'){
                $this->excel_NotasCursadas();
                return true;
            }else if($this->tipo_reporte == 'rend_catedras'){
                $this->excel_RendimientoDeCursada();

                    return true;    
                
            }else if($this->tipo_reporte == 'ins_cursada'){
                $this->excel_RegistroInscripcionCursada();
                return true;
            }

            return true;
        }else{
            $this->hasErrors('Búsqueda sin resultados');
            return false;
        }
    }

    // Generadores de excel
    private function excel_NotasCursadas()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerstyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 12,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $company_name_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $titlestyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 14,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $titledatestyle = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            )
        );

        $titleborder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $contentstyle = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_TOP,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $counterdata = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            )
        );

        $min_word = 'A';
        $max_word = 'K';


        $sheet->mergeCells($min_word . '1:' . $max_word . '2');
        $sheet->mergeCells($min_word . '5:' . $max_word . '6');
        $sheet->mergeCells($min_word . '7:' . $max_word . '7');

        $sheet->setCellValue($min_word . '1', $this->title);
        $sheet->setCellValue($min_word . '5', $this->subtitle);
        $sheet->setCellValue($min_word . '7', date("d/m/Y H:i"));

        $sheet
            ->setCellValue($min_word . '9', 'Nro. Doc')
            ->setCellValue('B9', 'Cod. Materia')
            ->setCellValue('C9', 'Cond. Regularidad')
            ->setCellValue('D9', 'Resultado')
            ->setCellValue('E9', 'Nota')
            ->setCellValue('F9', 'Fecha')
            ->setCellValue('G9', 'Nro. Libro')
            ->setCellValue('H9', 'Nro. Acta')
            ->setCellValue('I9', 'Folio')
            ->setCellValue('J9', 'Renglón')
            ->setCellValue($max_word . '9', 'Renglones Folio');

        foreach (range($min_word, $max_word) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $counter = 10;
        foreach ($this->rep as $row) {
            $sheet
                ->setCellValue($min_word . $counter, '' . $row['nro_documento'])
                ->setCellValue('B' . $counter, '' . $row['code_subject'])
                ->setCellValue('C' . $counter, '' . $row['cond_regularidad'])
                ->setCellValue('D' . $counter, '' . $row['resultado'])
                ->setCellValue('E' . $counter, '' . $row['nota'])
                ->setCellValue('F' . $counter, '' . $row['fecha'])
                ->setCellValue('G' . $counter, '' . $row['nro_libro'])
                ->setCellValue('H' . $counter, '' . $row['nro_acta'])
                ->setCellValue('I' . $counter, '' . $row['folio'])
                ->setCellValue('J' . $counter, '' . $row['renglon'])
                ->setCellValue($max_word . $counter, '' . $row['renglones_folio']);

            $counter++;
        }

        $counter = $counter + 2;
        $sheet->mergeCells($min_word . $counter . ':' . $max_word . $counter);
        $sheet->setCellValue($min_word . $counter, '' . 'Total de Resultados: ' . ($counter - 12));

        $sheet->getColumnDimension($min_word)->setAutoSize(false);
        $sheet->getColumnDimension($max_word)->setAutoSize(true);

        $sheet->getStyle($min_word . '1')->applyFromArray($company_name_style);
        $sheet->getStyle($min_word . '9:' . $min_word . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        $sheet->getStyle($min_word . '5')->applyFromArray($titlestyle);
        $sheet->getStyle($min_word . '7')->applyFromArray($titledatestyle);
        $sheet->getStyle($min_word . '5:' . $max_word . '7')->applyFromArray($titleborder);

        $sheet->getStyle('B5:' . $max_word . $sheet->getHighestRow())->applyFromArray($contentstyle);

        $sheet->getStyle($min_word . '9:' . $max_word . '9')->applyFromArray($headerstyle);
        $sheet->getStyle($min_word . '9:' . $max_word . '9')->getFont()->setBold(true);

        $sheet->getStyle($min_word . $counter)->applyFromArray($counterdata);

        $sheet->setShowGridLines(false);

        $spreadsheet->getActiveSheet()->setTitle($this->title);

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->name_arc . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        return true;
    }


     private function excel_RendimientoDeCursada()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $title_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 14,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $subtitle_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_border = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_border = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_style = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $min_word = 'A';
        $max_word = 'I';

        $sheet->mergeCells($min_word . '1:' . $max_word . '1');
        $sheet->mergeCells($min_word . '3:' . $max_word . '3');

        $sheet->setCellValue($min_word . '1', $this->title);
        $sheet->setCellValue($min_word . '3', $this->subtitle);

        $sheet
            ->setCellValue($min_word . '4', 'Materia')
            ->setCellValue('B4', 'Catedra')
            ->setCellValue('C4', 'Aprobados')
            ->setCellValue('D4', '%')
            ->setCellValue('E4', 'Reprobados')
            ->setCellValue('F4', '%')
            ->setCellValue('G4', 'Ausentes')
            ->setCellValue('H4', '%')
            ->setCellValue($max_word . '4', 'Total');

        foreach (range($min_word, $max_word) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $i = 5;
        foreach ($this->rep as $row) {
            $sheet
                ->setCellValue($min_word . $i, $row['materia'])
                ->setCellValue('B' . $i, $row['catedra'])
                ->setCellValue('C' . $i, $row['aprob'])
                ->setCellValue('E' . $i, $row['repro'])
                ->setCellValue('G' . $i, $row['ausen'])

                ->setCellValue($max_word . $i, '=SUM(C' . $i . '+E' . $i . '+G' . $i . ')')    // First, need the acum

                ->setCellValue('D' . $i, '=(+C' . $i . '/I' . $i . ')')
                ->setCellValue('F' . $i, '=(+E' . $i . '/I' . $i . ')')
                ->setCellValue('H' . $i, '=(+G' . $i . '/I' . $i . ')');
            $i++;
        }

        $sheet->getColumnDimension($min_word)->setAutoSize(false);
        $sheet->getColumnDimension($max_word)->setAutoSize(true);

        $sheet->getStyle($min_word . '1')->applyFromArray($title_style);
        $sheet->getStyle($min_word . '3')->applyFromArray($subtitle_style);

        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_style);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_border);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f8cbad');


        $sheet->getStyle('D4:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('F4:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('H4:H' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');


        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_style);
        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_border);

        $spreadsheet->getActiveSheet()->setTitle($this->name_arc);
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->name_arc . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        return true;
    }

    private function excel_RegistroInscripcionCursada()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $title_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 14,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $subtitle_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_style = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 11,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );

        $header_border = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_border = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => Border::BORDER_THIN, //BORDER_THIN BORDER_MEDIUM BORDER_HAIR
                    'color' => array('rgb' => '000000')
                )
            )
        );

        $table_style = array(
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_CENTER,
            )
        );

        $min_word = 'A';
        $max_word = 'H';

        $sheet->mergeCells($min_word . '1:' . $max_word . '1');
        $sheet->mergeCells($min_word . '3:' . $max_word . '3');

        $sheet->setCellValue($min_word . '1', $this->title);
        $sheet->setCellValue($min_word . '3', $this->subtitle);

        $sheet
            ->setCellValue('A4', 'Comisión')
            ->setCellValue('B4', 'Catedra')
            ->setCellValue('C4', 'Nro. Documento')
            ->setCellValue('D4', 'Apellido')
            ->setCellValue('E4', 'Nombres')
            ->setCellValue('F4', 'Email')
            ->setCellValue('G4', 'Usuario')
            ->setCellValue($max_word . '4', 'Materia');

        foreach (range($min_word, $max_word) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $i = 5;
        foreach ($this->rep as $row) {
            $sheet
                ->setCellValue('A' . $i, $row['comision'])
                ->setCellValue('B' . $i, utf8_encode($row['catedra']))
                ->setCellValue('C' . $i, $row['nro_documento'])
                ->setCellValue('D' . $i, utf8_encode($row['apellido']))
                ->setCellValue('E' . $i, utf8_encode($row['nombres']))
                ->setCellValue('F' . $i, $row['email'])
                ->setCellValue('G' . $i, $row['usuario'])
                ->setCellValue($max_word . $i, $row['materia']);
            $i++;
        }

        $sheet->getColumnDimension($min_word)->setAutoSize(false);
        $sheet->getColumnDimension($max_word)->setAutoSize(true);

        $sheet->getStyle($min_word . '1')->applyFromArray($title_style);
        $sheet->getStyle($min_word . '3')->applyFromArray($subtitle_style);

        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_style);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')->applyFromArray($header_border);
        $sheet->getStyle($min_word . '4:' . $max_word . '4')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('f8cbad');


        $sheet->getStyle('D4:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('F4:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');
        $sheet->getStyle('H4:H' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('0.00%');


        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_style);
        $sheet->getStyle('A4:' . $max_word . $sheet->getHighestRow())->applyFromArray($table_border);

        $spreadsheet->getActiveSheet()->setTitle($this->name_arc);
        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header('Content-Disposition: attachment;filename="' . $this->name_arc . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');


        

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        return true;
    }

}


?>