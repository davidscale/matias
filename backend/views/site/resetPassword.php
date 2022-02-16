<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <div class="form-group">
            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'placeholder' => 'Contraseña...'])->label(false) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 're_password')->passwordInput(['autofocus' => true, 'placeholder' => 'Repetir contraseña...'])->label(false) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-danger btn-block', 'name' => 'reset-button']) ?>
        </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
