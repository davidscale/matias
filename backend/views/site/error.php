<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <div class="row">

        <div class="col-sm-7 colum01">

            <h1><?= Html::encode($this->title) ?></h1>


            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>
    
            <p>
                <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.') ?>        
            </p>
            <p>
                <?= Yii::t('app', 'Please contact us if you think this is a server error. Thank you.') ?>    
            </p>
          
        </div>

        <div class="col-sm-5 colum02">
            <br><br>
              
            <img src="https://thumbs.dreamstime.com/b/perdido-icono-del-hombre-elemento-negativo-car%C3%A1cter-para-los-apps-m%C3%B3viles-concepto-y-web-detallado-se-puede-utilizar-nosotros-128895498.jpg" width="100%">
      
        </div>

    </div>

</div>

<style type="text/css">
    img{
        clip-path: circle(50.4% at 50% 50%);
        background-color: rgba(145, 145, 145, 0.282);
  
    }

    .colum01{
        padding-top: 10%;
    }
</style>
