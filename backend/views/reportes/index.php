<?php 

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Reportes';

?>

<div>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="container text-center">

        <form id="form" action="view" method="POST">

            <div class="container text-left">

                <div class="row">

                    <dev class="col-sm">
                        <select required name="kynd_report" class="custom-select my-1 mr-sm-2 required">
                            <option value="">Seleccione...</option>
                            <option value="primer">1º informe</option>
                        </select>
                    </dev>

                    <div class="col-sm">
                        <?= Html::submitButton(Yii::t('app', 'Ver Reporte'), ['class' => 'btn btn-danger btn-lg btn-block']) ?>
                    </div>

                </div>
            </div>

        </form>

    </div>

</div>
