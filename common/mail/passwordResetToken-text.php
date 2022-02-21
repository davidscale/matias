<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user backend\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app', 'Hello') ?><?= Html::encode($user->username) ?>,

<?= Yii::t('app', 'Follow the link below to verify your email:') ?>

<?= $verifyLink ?>
