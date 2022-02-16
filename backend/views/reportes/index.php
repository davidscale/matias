<?php 
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\db_guarani\Reportes_Form */ 

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaUbicaciones;
use backend\models\db_guarani\SgaPeriodos;

$this->title = 'Reportes';

$this->params['breadcrumbs'][] = $this->title;

//Datos de los select
$tipo_reporte= [
    'notas_cursadas' => 'Notas de Cursadas', 
    'rend_catedras' => 'Rendimiento académicos cátedras'];

// Obtengo todas las propuestas con estado 'A'
$propuestas = ArrayHelper::map(
    SgaPropuestas::find()
        ->where(['estado' => 'A'])
        ->all(),
    'propuesta',
    'nombre_abreviado'
);

// Obtengo todos loas años academicos en orden desc
$anio = ArrayHelper::map(
    SgaAniosAcademicos::find()
        ->orderBy(['anio_academico' => SORT_DESC])
        ->all(),
    'anio_academico',
    'anio_academico'
);


// Obtengo todas las Ubicaciones
$ubicacion = ArrayHelper::map(
    SgaUbicaciones::find()
        ->all(),
    'ubicacion',
    'nombre'
);

?>





<div>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="container text-center">

        <?php $form = ActiveForm::begin(['id' => 'report-form']); ?>

            <div class="container text-left">

                <div class="row">

                    <dev class="col-sm-9">
                        <dev class="col">
                            <?= $form->field($model, 'tipo_reporte')->dropDownList($tipo_reporte, [
                                    'id' => 'tipo_reporte',
                                    'onchange' => 'showInputsForm(this.value)'
                                ])->label('Tipo de reporte:'); ?>
                        </dev>

                        <hr>

                        <div class="row">
                            <div class="col-sm-6 forms notasCur-form">
                                <?= $form->field($model, 'propuesta')->dropDownList($propuestas, [
                                    'prompt' => 'Seleccione Propuesta...'])->label('Propuesta:'); ?>
                            </div>

                            <div class="col-sm-6 forms notasCur-form">
                                <?= $form->field($model, 'anio')->dropDownList($anio, [
                                    'prompt' => 'Seleccione un Año Académico...',
                                    //'required' => true,
                                    'onchange' => 'getPeriodos(this.value,"' . Yii::$app->request->baseUrl . '")'
                                    ])->label('Año Académico:'); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 forms notasCur-form">
                                <?= $form->field($model, 'ubicacion')->dropDownList($ubicacion)->label('Ubicación:'); ?>
                            </div>

                            <div class="col-sm-6 forms notasCur-form">
                                <?= $form->field($model, 'periodo')->dropDownList([], [
                                            'prompt' => 'Seleccione un Período',
                                            'id' => 'dropDownList_periodo',
                                        ])->label('Período:'); ?>
                            </div>
                        </div>
                        
                    </dev>

                    <div class="col-sm-3">
                        <?= Html::submitButton('Ver Reporte', [ 
                                    'name' => 'btn-view', 
                                    'class' => 'btn btn-danger btn-lg btn-block']) ?>

                        <?= Html::submitButton('Descargar Excel', [
                            'name' => 'btn-excel', 
                            'class' => 'btn btn-success btn-lg btn-block',                             
                            'onClick' => 'BtnAccion()']) ?>
       
                    </div>
                    

                </div>
            </div>

        
        <?php ActiveForm::end(); ?>

    </div>

</div>

<script type="text/javascript">

    // Funcion que indica los inputs a mostar
    function showInputsForm(dato) {

        let form = $('.forms');
        form.css("display", "none");

        if (!dato) {
            return;
        }else if(dato == "selecciome"){
            form = $('.notasCur-form');
            form.css("display", "none");
        }

        switch (dato) {
            case "seleccione":
                form = $('.notasCur-form');
                form.css("display", "none");
                break;

            case "notas_cursadas":
                form = $('.notasCur-form');
                form.css("display", "inherit");
                break;

            case "rend_catedras":
                form = $('.rendCat-form');
                form.css("display", "inherit");
                break;

            default:
                form = $('.notasCur-form');
                form.css("display", "none");
                break;
        }
    }

    // Paso el periodo por año
    function getPeriodos(year, url) {
        $.ajax({
            url: url + '/reportes/periodo',
            type: 'POST',
            data: {
                year: year
            },
            success: function(res) {
                $('#dropDownList_periodo').html(res);
            },
            error: function() {
                console.log("Error");
            }
        })
    }

    


    function BtnAccion() {
        document.getElementById("report-form").action = '';
        document.getElementById("report-form").action = 'generar_reporte';
    }
</script>