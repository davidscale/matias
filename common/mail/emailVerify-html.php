<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user backend\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verification', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p><?= Yii::t('app', 'Hello') ?><?= Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('app', 'Follow the link below to verify your email:') ?></p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink, ['class' => 'btn btn-danger btn-lg btn-block']) ?></p>
</div>
