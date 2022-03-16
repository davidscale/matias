<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \backend\models\db_guarani\Reportes_Form */

use app\models\db_guarani\SgaPeriodoGenerico;
use yii;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\Alert;

use backend\models\db_guarani\SgaPropuestas;
use backend\models\db_guarani\SgaAniosAcademicos;
use backend\models\db_guarani\SgaUbicaciones;

use kartik\spinner\Spinner;

$this->title = 'Reportes';

$this->params['breadcrumbs'][] = $this->title;

//Datos de los select
$tipo_reporte = [
    'seleccione' => 'Seleccione...',
    'notas_cursadas' => 'Notas de Cursadas (para académico)',
    'rend_catedras' => 'Rendimiento Académicos de  Cátedras',
    'ins_cursada' => 'Reporte de Inscripción a Cursada'
];

// Obtengo todas las propuestas con estado 'A' 
$propuestas = ArrayHelper::map(
    SgaPropuestas::find()
        ->where(['estado' => 'A'])
        ->all(),
    'propuesta',
    'nombre_abreviado'
);

// Obtengo todos los periodos con activo 'S' 
$cuatrimestre = ArrayHelper::map(
        SgaPeriodoGenerico::find()
            ->where(['activo' => 'S'])
            ->select(['periodo_lectivo_tipo'])  //TODO::
            ->distinct('periodo_lectivo_tipo')
            ->all(),
    'periodo_lectivo_tipo',
    'periodo_lectivo_tipo'
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
    <?= Alert::widget() ?>
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="container text-center">

        <?php $form = ActiveForm::begin(['id' => 'report-form']); ?>

        <div class="container text-left">

            <?= Alert::widget() ?>

            <div class="row">

                <dev class="col-sm">
                    <dev class="col">
                        <?= $form->field($model, 'tipo_reporte')->dropDownList($tipo_reporte, [
                            'id' => 'tipo_reporte',
                            'onchange' => 'showInputsForm(this.value)'
                        ])->label('Tipo de reporte:'); ?>
                    </dev>

                    

                    <div id='esto'>
                        
                        <hr>

                        <div class="row" id="propuesta_anio">
                            <div class="col-sm-6 forms notasCur-form insCur-form">
                                <?= $form->field($model, 'propuesta')->dropDownList($propuestas, [
                                    'prompt' => 'Seleccione Propuesta...'
                                ])->label('Propuesta:'); ?>
                            </div>

                            <div class="col-sm-6 forms notasCur-form rendCat-form insCur-form">
                                <?= $form->field($model, 'anio')->dropDownList($anio, [
                                    'id' => 'dropDownList_year',
                                    'prompt' => 'Seleccione un Año Académico...',
                                ])->label('Año Académico:'); ?>
                            </div>
                        </div>

                        <div class="row" id='periodo'>
                            <div class="col-sm-6 forms notasCur-form rendCat-form insCur-form">
                                <?= $form
                                    ->field($model, 'cuatrimestre')
                                    ->dropDownList(
                                        $cuatrimestre,
                                        [
                                            'onchange' => 'getPeriodos(this.value,"' . Yii::$app->request->baseUrl . '")'
                                        ]
                                    )
                                    ->label('Tipo de Período:'); ?>
                            </div>

                            <div class="col-sm-6 forms notasCur-form rendCat-form insCur-form">
                                <?= $form->field($model, 'periodo')->dropDownList([], [
                                    'prompt' => 'Seleccione un Período',
                                    'id' => 'dropDownList_periodo',
                                    'onchange' => 'getElementos(this.value,"' . Yii::$app->request->baseUrl . '")'
                                ])->label('Período:'); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 forms notasCur-form insCur-form">
                                <?= $form->field($model, 'ubicacion')->dropDownList($ubicacion)->label('Ubicación:'); ?>
                            </div>

                            <div class="col-sm-6 forms insCur-form">
                                <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target=".bs-example-modal-lg-add">
                                    <?php echo Yii::t('app', 'Selection of subjects that you do not want to show'); ?>
                                </button>
                            </div>
                            
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm">
                                <div id="btn-excel">
                                    <?= Html::submitButton('Descargar Excel', [
                                        'id' => 'btn-excel',
                                        'name' => 'btn-excel',
                                        'class' => 'btn btn-success btn-lg btn-block',
                                        'onClick' => 'BtnAccion()'
                                    ]) ?>
                                </div>
                                <div id="btnSpinner">
                                    <?php
                                    echo '<button class="btn btn-success btn-lg btn-block" disabled>';
                                    echo Spinner::widget([
                                        'preset' => 'tiny',
                                        'align' => 'left',
                                        'caption' => 'Descargando...'
                                    ]);
                                    echo '</button>';
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>                   
                </dev>

                <!-- Modal -->
                <div class="modal fade bs-example-modal-lg-add" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header alert-primary">

                                <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app', 'Selection of subjects that you do not want to show'); ?></h4>

                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <div class="modal-body text-center">

                                <label id="lbl-no-element"><?php echo Yii::t('app', 'Please, first select a Period'); ?></label>

                                <?= $form
                                    ->field($model, 'elements')
                                    ->label(false)
                                    ->inline()
                                    ->checkboxList(
                                        [],
                                        ['id' => 'checkboxList_element']
                                    ); ?>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- /Modal -->

                


            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>

</div>

<script type="text/javascript">
    document.getElementById("btnSpinner").hidden = true;
    document.getElementById("esto").hidden = true;   

    // Funcion que indica los inputs a mostar
    function showInputsForm(dato) {

        let form = $('.forms');
        form.css("display", "none");


        if (!dato) {
            return;
        }
 
        switch (dato) {
            case "seleccione":
                form = $('.notasCur-form');
                form.css("display", "none");
                break;

            case "notas_cursadas":
                document.getElementById("esto").hidden = false;                
                form = $('.notasCur-form');
                form.css("display", "inherit");
                break;

            case "rend_catedras":

                document.getElementById("esto").hidden = false;   
                form = $('.rendCat-form');
                form.css("display", "inherit");
                break;

            case 'ins_cursada':

                document.getElementById("esto").hidden = false;   
                form = $('.insCur-form');
                form.css("display", "inherit");
                break;

            default:
                form = $('.notasCur-form');
                form.css("display", "none");
                break;
        }
    }

    // Paso el periodo por año
    function getPeriodos(cuatrimestre, url) {
        let data_year;
        
        if (data_year = $('#dropDownList_year').val()) {
            $.ajax({
                url: url + '/reportes/periodo',
                type: 'POST',
                data: {
                    year: data_year,
                    cuatrimestre: cuatrimestre
                },
                success: function(res) {
                    $('#dropDownList_periodo').html(res);
                },
                error: function() {
                    console.log("Error");
                }
            })
        }
    }

    // Paso el periodo por año
    function getComisiones(comision, url) {
        $.ajax({
            url: url + '/reportes/comision',
            type: 'POST',
            data: {
                comision: comision
            },
            success: function(res) {
                $('#dropDownList_comision').html(res);
            },
            error: function() {
                console.log("Error");
            }
        })
    }

    function getElementos(periodo, url) {
        $.ajax({
            url: url + '/reportes/elemento',
            type: 'POST',
            data: {
                periodo: periodo
            },
            success: function(res) {
                res = JSON.parse(res);
                html = '';
                count = 0;

                res.forEach(r => {
                    html += '<div class="custom-control custom-checkbox custom-control-inline">';
                    html += '<input type="checkbox" id="i' + count + '" class="custom-control-input" name="ReportForm[elements][]" value="' + r.codigo + '">';
                    html += '<label class="custom-control-label" for="i' + count + '"> ' + r.nombre + '</label>';
                    html += '</div><br><br>';
                    count++;
                });

                $('#lbl-no-element').hide();
                $('#checkboxList_element').html(html);
            },
            error: function() {
                console.log("Error");
            }
        })
    }

    function BtnAccion() {
        document.getElementById("report-form").action = 'generar';

        let dato = document.getElementById("tipo_reporte");

        if(dato == 'rend_catedras'){
            //MEJORAR
            document.getElementById("btnSpinner").hidden = false;
            document.getElementById("btn-excel").hidden = true;
            setTimeout('hideBtnSpinner()', 2000);

        } else {
            //MEJORAR
            document.getElementById("btnSpinner").hidden = false;
            document.getElementById("btn-excel").hidden = true;
            setTimeout('hideBtnSpinner()', 10000);
        }

        
    }

    function hideBtnSpinner() {
        document.getElementById("btnSpinner").hidden = true;
        document.getElementById("btn-excel").hidden = false;
    }
</script>