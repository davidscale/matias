<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Reportes: ' . $kynd_report;
?>


<div class="report-view">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="container text-center py-2 bg-secondary">

        <?php var_dump($report[0]); ?>


        <div class="form-group mt-4">
            <form id="form" action="report/export" method="POST">

                <?php echo Html::hiddenInput($csrf, $token); ?>
                <!-- No idea, but i need that.. -->
                <?= Html::submitButton(Yii::t('app', 'Export'), ['class' => 'btn btn-success']) ?>

            </form>
        </div>

    </div>

</div>