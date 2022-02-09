<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use kartik\grid\GridView;

class ReportesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    

    public function actionView()
    {
        var_dump($_POST['kynd_report']);die;
        
        if (isset($_POST) && $_POST) {

            if ($query = $this->getQueryByKynd($_POST['kynd_report'])) {
                $data = Yii::$app->db_guarani->createCommand($query)->queryAll();
                $data = json_decode(json_encode($data), FALSE);

                if (count($data) > 0) {
                    return $this->render(
                        'reportes/view',
                        array(
                            'data' => $data,
                            'kynd_report' => $_POST['kynd_report']
                        )
                    );
                } else {
                    // empty..
                }
            }
        }
        //return $this->actionIndex();    // If something is wrong, back to form of reports
    }

    private function getQueryByKynd($kynd)
    {

        
        $query = NULL;
        switch ($kynd) {
            case 'primer':
                $query = "select 
                    --per.apellido,
                    --per.nombres,
                    pd.nro_documento,
                    --co.nombre as comision,
                    ma.codigo as materia,
                    --ma.codigo,
                    ad.cond_regularidad,
                    case
                    when (ad.resultado = 'A') then 'P'
                    when (ad.resultado = 'R') then 'N'
                    when (ad.resultado = 'U') then 'U'
                    end as resultado,
                    case 
                    when (ad.nota = 'Ausente') then ''
                    else ad.nota
                    end as nota,
                    to_char(ad.fecha, 'DD/MM/YYYY') as fecha,
                    libro.nro_libro,
                    acta.nro_acta,
                    ad.folio,
                    ad.renglon,
                    acta.renglones_folio
                    --acta.origen
                    from negocio.sga_comisiones as co
                    join negocio.sga_elementos as ma on co.elemento = ma.elemento
                    join negocio.sga_actas as acta on co.comision = acta.comision
                    left join negocio.sga_actas_detalle as ad on acta.id_acta = ad.id_acta
                    join negocio.sga_actas_folios as af on acta.id_acta = af.id_acta and af.folio = ad.folio
                    join negocio.sga_libros_tomos as lt on af.libro_tomo = lt.libro_tomo
                    join negocio.sga_libros_actas as libro on lt.libro = libro.libro
                    left join negocio.sga_cond_regularidad as cr on ad.cond_regularidad = cr.cond_regularidad
                    left join negocio.sga_escalas_notas_resultado as enr on ad.resultado = enr.resultado
                    join negocio.sga_alumnos as alu on ad.alumno = alu.alumno
                    join negocio.mdp_personas as per on alu.persona = per.persona
                    join negocio.mdp_personas_documentos as pd on alu.persona = pd.persona
                    where co.periodo_lectivo = 189 and co.ubicacion = 1 and acta.origen = 'P'
                    order by 1,3";
                break;


            default:
                var_dump("nadaaaaaaaaaaaaaaaa...."); die;
                break;
        }
        return $query;
    }

    public function actionExport()
    {   
        
        if (isset($_POST) && $_POST) {

            if ($query = $this->getQueryByKynd($_POST['kynd_report'])) {
                $data = Yii::$app->db_guarani->createCommand($query)->queryAll();
                $data = json_decode(json_encode($data), FALSE);

                if (count($data) > 0) {
                    $this->generateExcel($data);
                } else {
                    // empty..
                }

                return $this->render(
                    'view',
                    array(
                        'data' => $data,
                        'kynd_report' => $_POST['kynd_report']
                    )
                );
            }
        }
        return $this->actionIndex();    // If something is wrong, back to form of reports
    }

    private function generateExcel($data)
    {
        // https://demos.krajee.com/grid-excel-export-demo/1

        var_dump("acá comenzará EXCEL");
        die();

        foreach ($data as $row) {
            // $row->nro_documento
            // $row->materia
            // $row->cond_regularidad
            // $row->resultado
            // $row->nota
            // $row->fecha
            // $row->nro_libro
            // $row->nro_acta
            // $row->folio
            // $row->renglon
            // $row->renglones_folio
            // $row->origen
        }
    }

}
