<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user backend\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>

<?= Yii::t('app', 'Hello') ?><?= Html::encode($user->username) ?>,

<?= Yii::t('app', 'Follow the link below to verify your email:') ?>

<p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
