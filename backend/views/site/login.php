<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Bienvenido';
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


    <div class="site-login">
        <div class="offset-lg-3 col-lg-6">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>


            <img class="img-thumbnail img-fluid my-1" alt="img-log" src="<?php echo $imagen; ?>">


            <p><b><?= Yii::t('app', 'Please fill in the following fields to login:') ?></b></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput()->label('Usuario:') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Contraseña:') ?>            

                <div class="form-group">
                    <?= Html::submitButton('Iniciar Sesión', ['class' => 'btn btn-danger btn-block', 'name' => 'login-button']) ?>
                </div>

                <div class="my-2 d-flex flex-row justify-content-between">
                    <a href="/" >Olvidaste tu contraseña?</a>
                    <a href="./signup">No estoy registrado</a>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


