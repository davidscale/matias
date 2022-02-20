<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="user-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput()->label('Dni:'); ?>
    
        <?= $form->field($model, 'email')->label('Email:');  ?>
    
        <?php if(!$update){ ?>
            <?= $form->field($model, 'password_hash')->passwordInput()->label('Contrasña:');  ?>

            <?= $form->field($model, 're_password')->passwordInput()->label('Repetir Contrasña:');  ?>
        <?php } ?>
    
        <div class="form-group mb-3">
            <?= $form->field($model, 'status')->dropDownList([
                                                    9 => 'Inactivo',
                                                    10 => 'Activo'])->label('Estado del Usuario:')  ?>
        </div>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger btn-lg btn-block']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>

    

</div>
