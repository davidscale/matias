<?php



namespace backend\controllers;

use Yii;
use yii\web\Controller;
use kartik\grid\GridView;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use backend\models\db_guarani\Reportes_Form;
use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaPeriodos;
use backend\models\db_guarani\SgaComisiones;



class ReportesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'primerInforme',
                            'generar_reporte',
                            'periodo',
                            'comision',
                            'acta',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Reportes_Form();
        if ($model->load(Yii::$app->request->post())) {
            return $this->actionView($model);
        }
        return $this->render('index', [
            'model' => new Reportes_Form()
        ]);
    }

    
    // Se lista si hay algo q mostrar
    public function actionView()
    {                  
        $model = new Reportes_Form();     
        if ($data = $model->generar_reporte()) {
            return $this->render('view', [
                'model' => $model,
                'data' => $data
            ]);
        }else{
            Yii::$app->session->setFlash('danger', 'Nos se encontro informacion, segun los datos ingresados!');
        }

        return $this->actionIndex();
        // if (isset($_POST['val_reporte']) && $_POST['val_reporte']) {

        //     switch ($_POST['val_reporte']) {
        //             case 'primer':
        //                 return $this->render('primerInforme');
        //                 break;


        //             default:
        //                 var_dump("nadaaaaaaaaaaaaaaaa...."); die;
        //                 break;
        //         }

            // if ($query = $this->getQueryByKynd($_POST['kynd_report'])) {
            //     $data = Yii::$app->db_guarani->createCommand($query)->queryAll();
            //     $data = json_decode(json_encode($data), FALSE);

            //     if (count($data) > 0) {
            //         return $this->render(
            //             'reportes/view',
            //             array(
            //                 'data' => $data,
            //                 'kynd_report' => $_POST['kynd_report']
            //             )
            //         );
            //     } else {
            //         // empty..
            //     }

                
            // }
        // }
        //return $this->actionIndex();    // If something is wrong, back to form of reports
    }

    private function actionPrimerInforme(){
        return $this->render('primerInforme');
    }

    // Genera el reporte
    public function actionGenerar_reporte()
    {
        $model = new Reportes_Form();
        if ($model->load(Yii::$app->request->post()) && $model->generar_reporte_excel()) {

            $model->generar_reporte_excel();
            Yii::$app->session->setFlash('success', 'Reporte generado');
        }
        return $this->actionIndex();
    }

    public function actionPeriodo()
    {
        if ($_POST['year']) {
            $data = SgaPeriodos::find()
                ->where(['anio_academico' => $_POST['year']])
                ->all();

            $rta = '<option value="">Seleccione un Período...</option>';
            foreach ($data as $p) {
                $rta .= '<option value="' . $p->periodo . '"> ' . utf8_encode($p->nombre) . '</option>';
            }

            echo $rta;
        }
    }

    

    

}
