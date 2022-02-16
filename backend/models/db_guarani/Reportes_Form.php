<?php 

namespace backend\models\db_guarani;


use Yii;
use yii\base\Model;

use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaPeriodos;
use backend\models\db_guarani\SgaComisiones;

// Lib. de PhpOffice
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PHPOffice\PhpSpreadsheet\Style\Alignment;


class Reportes_Form extends Model{

    private $rep;

    // Variables para los reportes
    public $tipo_reporte;    
    public $propuesta;    
    public $anio;
    public $ubicacion;
    public $periodo;

    // Variables para el excel
    private $title;
    private $subtitle;
    private $name_arc;

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
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
            [['anio', 'periodo', 'periodo_lectivo'], 'required',
                'when' => function ($model) {
                    return $model->tipo_reporte == 'rend_catedras';
                },
                'whenClient' => "function (attribute, value) {
                                    return $('#tipo_reporte').val() == 'rend_catedras';
                                }"],

        ];
    }

    // Seleccion de query 
    private function getQuery()
    {
        $rta = null;

        switch ($this->tipo_reporte) {
            case 'notas_cursadas':
                $rta = "SELECT 
                    per.apellido,
                    per.nombres,
                    pd.nro_documento,
                    co.nombre AS comision,
                    ma.codigo AS materia,
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
                    JOIN sga_elementos AS ma ON co.elemento = ma.elemento
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
                    JOIN sga_periodos_lectivos AS pl ON pl.periodo = " . $this->periodo . " AND co.periodo_lectivo = pl.periodo_lectivo
                    JOIN sga_periodos AS ps ON pl.periodo = ps.periodo

                    WHERE co.ubicacion = " . $this->ubicacion . " AND acta.origen = 'P'
                    ORDER BY 1,3";
                break;

            case 'rend_catedras':

                break;
        }
        return $rta;
    }

    // Retorna un reporte luego de una busqueda de los datos ingresados
    public function generar_reporte()
    {
        //ini_set('memory_limit', '-1');
        if ($this->validate()) {
            $query = $this->getQuery();
            $data = Yii::$app->db_guarani->createCommand($query)->queryAll();

            if ($data) {
                $this->rep = (object) $data;

                //asigno los titulos
                if($this->tipo_reporte == 'notas_cursadas'){
                    $this->title = 'NOTAS DE CURSADA';
                    $this->subtitle = '';
                    $this->name_arc = 'Notas de Cursada';
                }

                return $this->rep;
            } else {
                $this->hasErrors('Búsqueda sin resultados');
            }
        }
        return null;
    }

    // Funcion que genera los excel
    public function generar_reporte_excel(): bool
    {
        // It will take unlimited memory usage of server, it's working fine.
        ini_set('memory_limit', '-1');

        if ($this->generar_reporte()) {
            if($this->tipo_reporte == 'notas_cursadas'){
                $this->excel_NotasCursadas();
            }

            return true;
        }
        return false;
    }

    // Generadores de excel
    private function excel_NotasCursadas(): void
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
                ->setCellValue('B' . $counter, '' . $row['materia'])
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
        exit;
    }






}


?>