<?php



namespace backend\controllers;

use Yii;
use yii\web\Controller;
use kartik\grid\GridView;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use backend\models\db_guarani\Reportes_Form;
use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaPeriodos;
use backend\models\db_guarani\SgaComisiones;
use backend\models\db_guarani\SgaActas;
use backend\models\db_guarani\SgaActasDetalle;



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
                            'generar',
                            'periodo',
                            'comision',
                            'elemento',
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

    public function actionView()
    {
        return $this->actionIndex();
    }

    private function actionPrimerInforme()
    {
        return $this->render('primerInforme');
    }

    // Genera el reporte
    public function actionGenerar()
    {
        $model = new Reportes_Form();
        
        // if ($model->load(Yii::$app->request->post()) && $model->generar_reporte_excel()) {
        if ($model->load(Yii::$app->request->post())) {
            if($model->generar_reporte_excel()){
                //var_dump('asdas');die;

                Yii::$app->session->setFlash('success', 'Reporte generado');
                return $this->render('index', [
                    'model' => new Reportes_Form()
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'No hay informacion segun los datos cargados...');

                return $this->render('index', [
                    'model' => new Reportes_Form()
                ]);
            }

        } else {

            Yii::$app->session->setFlash('error', 'Error...');

            return $this->render('index', [
                'model' => new Reportes_Form()
            ]);
        }
        return $this->actionIndex();
    }

    public function actionPeriodo()
    {
        if ($_POST['year'] && $_POST['cuatrimestre']) {

            $year = $_POST['year'];
            $cuatrimestre = $_POST['cuatrimestre'];

            $command = "SELECT 
                    p.nombre, 
                    p.periodo

                FROM sga_periodos p

                LEFT JOIN sga_periodos_genericos pg ON p.periodo_generico = pg.periodo_generico

                WHERE p.anio_academico = '$year'
                AND pg.periodo_lectivo_tipo = '$cuatrimestre'";

            $data = Yii::$app->db_guarani->createCommand($command)->queryAll();

            $rta = '<option value="">Seleccione un Período...</option>';
            foreach ($data as $p) {
                $rta .= '<option value="' . $p['periodo'] . '"> ' . utf8_encode($p['nombre']) . '</option>';
            }
            echo $rta;
        }
    }

    public function actionComision()
    {
        if ($_POST['comision']) {
            $data = SgaComisiones::find()
                ->where(['periodo_lectivo' => $_POST['comision']])
                ->all();

            $rta = '<option value="">Seleccione una Comision...</option>';
            foreach ($data as $p) {
                $rta .= '<option value="' . $p->comision . '"> ' . utf8_encode($p->nombre) . '</option>';
            }

            echo $rta;
        }
    }

    public function actionActa()
    {
        if ($_POST['comision']) {
            $data = SgaActas::find()
                ->where([
                    'comision' => $_POST['comision'],
                    'origen' => 'P'
                ])
                ->all();

            $rta = '<option value="">Seleccione un Acta...</option>';
            foreach ($data as $p) {
                $rta .= '<option value="' . $p->nro_acta . '"> ' . utf8_encode($p->nro_acta) . '</option>';
            }

            echo $rta;
        }
    }

    public function actionElemento()
    {
        if ($periodo = $_POST['periodo']) {
            $command = "SELECT 
                    e.nombre, 
                    e.codigo

                FROM sga_elementos e

                LEFT JOIN sga_comisiones co ON e.elemento = co.elemento
                LEFT JOIN sga_periodos_lectivos AS pl ON co.periodo_lectivo = pl.periodo_lectivo 
                LEFT JOIN sga_periodos AS ps ON pl.periodo = ps.periodo

                WHERE pl.periodo = $periodo
                AND e.estado = 'A'
                
                GROUP BY e.nombre, e.codigo
                ORDER BY e.nombre";

            $data = Yii::$app->db_guarani->createCommand($command)->queryAll();
            echo json_encode($data);
        }
    }
}
