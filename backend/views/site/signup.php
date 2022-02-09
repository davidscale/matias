<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Registrarse';
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
    
    .fondo{
        background-image: url("https://media-exp1.licdn.com/dms/image/C561BAQHWc14MS-vB4w/company-background_10000/0/1519800673124?e=2159024400&v=beta&t=LqiRJQXOwnbijVuOPETYkwtAVF85a4hwGf2omWtjEj4");
        height:100%;
        width: 100%;
        background-position:center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    }

    h1, a{
        color: red;
    }



</style>

<div>
    <div class="offset-lg-3 col-lg-6">

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <img class="img-thumbnail img-fluid my-1" alt="img-log" src="<?php echo $imagen; ?>">

        <p><?= Yii::t('app', 'Please fill out the following fields to signup:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <div class="row">

                <div class="col-lg-12">

                    <?= $form->field($model, 'username')->textInput()->label('Dni:') ?>
                    
                </div>

                 <div class="col-lg-12">

                    <?= $form->field($model, 'email')->textInput()->label('Email:') ?> 
                    
                </div>

                <div class="col-lg-6">
                    <?= $form->field($model, 'password')->passwordInput()->label('Contraseña:') ?> 
                </div>

                <div class="col-lg-6">
                    <?= $form->field($model, 're_password')->passwordInput()->label('Repetir Contraseña:') ?> 
                </div>

            </div>

            <div class="form-group">
                <?= Html::submitButton('Registrarme', ['class' => 'btn btn-danger btn-block', 'name' => 'signup-button']) ?>
            </div>

            <div class="my-2 d-flex flex-row justify-content-between">
                <a href="./login">Ya tengo cuenta</a>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
