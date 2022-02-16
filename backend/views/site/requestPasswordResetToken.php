<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Request password reset';
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

<div class="site-request-password-reset">

    <div class="offset-lg-3 col-lg-6">
        <h1> <?= Yii::t('app', $this->title) ?></h1>

    <img class="img-thumbnail img-fluid my-1" alt="img-log" src="<?php echo $imagen; ?>">

    <p><?= Yii::t('app', 'Please complete with your ID. There, a link to reset the password will be sent to the associated email.') ?></p>

    
            <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-danger btn-block', 'name' => 'login-button']) ?>
                </div>

                <div class="my-2 d-flex flex-row justify-content-between">
                    <a href="./login">Iniciar Sesión</a>
                </div>

            <?php ActiveForm::end(); ?>
        
    </div>
    
</div>
