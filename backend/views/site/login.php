<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Facultad de Derecho';
$this->params['breadcrumbs'][] = $this->title;
?>




<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<style type="text/css">
    @charset "utf-8";

    body{
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;

        background: #0264d6; /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover,  #0264d6 1%, #1c2b5a 100%); /* FF3.6+ */
        background: -webkit-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* IE10+ */
        background: radial-gradient(ellipse at center,  #0264d6 1%,#1c2b5a 100%); /* W3C */
        height:calc(100vh);
        width: 100%;

    }
    
    
    
    /* ---------- GENERAL ---------- */
    
    * {
        box-sizing: border-box;
        margin:0px auto;
    
      &:before,
      &:after {
        box-sizing: border-box;
      }
    
    }
    
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
    
    input {
        border: none;
        font-family: 'Open Sans', Arial, sans-serif;
        font-size: 25px;
        line-height: 1.5em;
        padding: 0;
        -webkit-appearance: none;
    }
    
    p {
        font-size: 26px;
        line-height: 1.5em;
    }
    
    .clearfix {
      *zoom: 1;
    
      &:before,
      &:after {
        content: ' ';
        display: table;
      }
    
      &:after {
        clear: both;
      }
    
    }
    
    .container {
      left: 50%;
      position: fixed;
      top: 50%;
      transform: translate(-50%, -50%);
    }
    
    /* ---------- LOGIN ---------- */
    
    #login form{
        width: 250px;
    }
    #login, .logo{
        display:inline-block;
        width:40%;
    }
    #login{
    border-right:1px solid #fff;
      padding: 0px 22px;
      width: 59%;
    }
    .logo{
    color:#fff;
    font-size:50px;
      line-height: 125px;
    }
    
    #login form span.fa {
        background-color: #fff;
        border-radius: 3px 0px 0px 3px;
        color: #000;
        display: block;
        float: left;
        height: 50px;
        font-size:24px;
        line-height: 50px;
        text-align: center;
        width: 50px;
    }
    
    #login form input {
        height: 50px;
    }
    fieldset{
        padding:0;
        border:0;
        margin: 0;
    
    }
    #login form input[type="text"], input[type="password"] {
        background-color: #fff;
        border-radius: 0px 3px 3px 0px;
        color: #000;
        margin-bottom: 1em;
        padding: 0 16px;
        width: 200px;
    }
    
    #login form input[type="submit"] {
      border-radius: 3px;
      -moz-border-radius: 3px;
      -webkit-border-radius: 3px;
      background-color: #000000;
      color: #eee;
      font-weight: bold;
      /* margin-bottom: 2em; */
      text-transform: uppercase;
      padding: 5px 10px;
      height: 30px;
    }
    
    #login form input[type="submit"]:hover {
        background-color: #d44179;
    }
    
    #login > p {
        text-align: center;
    }
    
    #login > p span {
        padding-left: 5px;
    }
    .middle {
      display: flex;
      width: 700px;
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

<body>   
    <div class="main">   
        <div class="container">
            <center>
                <!-- <h1 class="text-center"><!?= Html::encode($this->title) ?></h1> -->
                <br><br>
                <div class="middle">
                        <div id="login">           
                                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                                <fieldset class="clearfix">  

                                    <div class="input-group">
                                        <div>
                                            <span class="input-group-text fa fa-user" id="basic-addon1"></span>
                                        </div>
                                        <?= $form->field($model, 'username')->textInput(['class' => 'form-control',
                                            'placeholder' => 'Dni...', 
                                            'value' => 'matias'])->label(false) ?>
                                    </div>


                                    <div >
                                        <div>
                                            <span class="input-group-text fa fa-lock" id="basic-addon1"></span>
                                        </div>
                                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control',
                                            'placeholder' => 'Contraseña...', 
                                            'value' => '12345678'])->label(false) ?>   
                                    </div>    
                          
                
                                    <div class="input-group">
                                        <?= Html::submitButton('Iniciar Sesión', [
                                            'class' => 'btn btn-danger btn-lg btn-block ', 
                                            'name' => 'login-button'
                                        ]) ?>
                                    </div>
        
                                    <div class="my-2 d-flex flex-row justify-content-between">
                                        <a href="./request-password-reset" >Olvidaste tu contraseña?</a>
                                        <!-- <a href="./signup">No estoy registrado</a> -->
                                    </div>
                                </fieldset>   

                                <?php ActiveForm::end(); ?>   

                            <div class="clearfix"></div>
                        </div> <!-- end login -->

                        <div class="logo">

                            <img  src="<?php echo $imagen; ?>">
              
                            <div class="clearfix"></div>
                        </div>
                </div>
            </center>
        </div>
    </div>
</body>



