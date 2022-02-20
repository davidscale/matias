<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\ArrayHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- <link rel="stylesheet" href="<= Url::to('./../web/css/site.css') ?>"> -->
    
    
    <?php 



    $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<style type="text/css">
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


         /*color: #eee;*/
        font: 100%/1em 'Open Sans', sans-serif;

    }

    .midiv {
        background-color: #e9ecef;
            border-radius: 0.5rem;

            box-shadow: 10px 10px 27px 0px rgba(255, 255, 255, 0.35);
            -webkit-box-shadow: 10px 10px 27px 0px rgba(255, 255, 255, 0.35);
            -moz-box-shadow: 10px 10px 27px 0px rgba(255, 255, 255, 0.35);


    }

    .miSegDiv{
        margin: 3%;
    }

</style>

<body class="d-flex flex-column h-100">


<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            ['label' => 'Administrar Usuarios', 'url' => ['/user']],
            ['label' => 'Reportes' , 'url' => ['/reportes/index']],
            //['label' => 'Reportes' , 'url' => ['/site/probando']],
        ]; 

        

        $menuItems[] = '<li>' 
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Cerrar sesion (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0 ">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="midiv"> 
            <div class="miSegDiv">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>   
            <br>         
        </div>
        
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
