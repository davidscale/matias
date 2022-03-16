<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */


?>
<div class="site-verificacion">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 're_password')->passwordInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-danger btn-lg btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>


