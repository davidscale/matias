<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */


?>
<div class="site-verification">
    <?php $form = ActiveForm::begin(); ?>

    <h3><?= Yii::t('app', 'Please set your password:') ?></h3>

    <?= $form->field($model, 'password')->passwordInput()->label('Contrasña:');  ?>

    <?= $form->field($model, 're_password')->passwordInput()->label('Repetir Contrasña:');  ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-danger btn-lg btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


