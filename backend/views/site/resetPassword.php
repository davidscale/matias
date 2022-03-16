<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model backend\models\ResetPasswordForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use common\widgets\Alert;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">


    <?php $form = ActiveForm::begin(); ?>

    <h3><?= Yii::t('app', 'Please set a new password:') ?></h3>

    <?= $form->field($model, 'password')->passwordInput()->label('Contrasña:');  ?>

    <?= $form->field($model, 're_password')->passwordInput()->label('Repetir Contrasña:');  ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-danger btn-lg btn-block']) ?>
    </div>

    <?= Alert::widget() ?>
    <br>
</div>
