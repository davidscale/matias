<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user backend\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verification', 'token' => $user->verification_token]);
?>

<?= Yii::t('app', 'Hello') ?><?= Html::encode($user->username) ?>,

<?= Yii::t('app', 'Please follow the link below to verify your email and set a password:') ?>

<p><?= Html::a('Establecer una nueva Contraseña', $verifyLink, ['class' => 'btn btn-danger btn-lg btn-block']) ?></p>
