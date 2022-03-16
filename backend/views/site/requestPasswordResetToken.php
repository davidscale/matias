<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

use common\widgets\Alert;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<style type="text/css">
    
    
    body {
       
        color: #eee;
        font: 100%/1em 'Open Sans', sans-serif;
        
    }
    
    a {
        color: #eee;
        text-decoration: none;
    }
    
    a:hover {
        text-decoration: underline;
    }
    
    
    p {
        font-size: 26px;
        line-height: 1.5em;
    }
    

    .miClass{
        left: 50%;
        position: fixed;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    

    .logo {
        display: inline-block;
        position: relative;
        width: 250px;
        height: 250px;
        overflow: hidden;
        border-radius: 25%; /*para logo unlz*/
        /*border-radius: 50%;*/ /*para logo edif*/

    }

    .logo img {

        width: auto;
        height: 100%;
    }

</style>

<div class="site-request-password-reset">

    <div class="main">   
        <div class="container">
            
        <?= Alert::widget() ?>
            <div class="miClass">
                <!-- <h1 class="text-center"><!?= Html::encode($this->title) ?></h1> -->
                <br><br>    
                    <div class="row">
                        <div class="col-sm-9">
                             <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>
                               
                                    <p><?= Yii::t('app', 'Please complete with your ID. There, a link to reset the password will be sent to the associated email.') ?></p>

                                    <div> 
                                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control',
                                            'placeholder' => 'Email...'])->label(false);  ?>
                                    </div>
                                      
                          
                
                                    <div class="input-group">
                                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-danger btn-lg btn-block', 'name' => 'login-button']) ?>
                                    </div>
        
                                    <div class="my-2 d-flex flex-row justify-content-between">
                                        <a href="./login" >Iniciar Sesión</a>
                                    </div>  

                                <?php ActiveForm::end(); ?>  
                        </div>

                        <div class="col-sm-3">
                            <div class="logo">

                            <img  src="<?php echo $imagen; ?>">
              
                        </div>
                        </div>
                    </div>                        
            </div>
        </div>
    </div>

    
    
</div>
