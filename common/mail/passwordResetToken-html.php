<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $user backend\models\User */
/* @var $form yii\bootstrap4\ActiveForm */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">

    <p><?= Yii::t('app', 'Hello') ?><?= Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('app', 'Click on the following link to reset a new password:') ?></p>

    <p>
<?= Html::a('Cambiar contraseña...', $verifyLink, ['class' => 'btn btn-danger btn-lg btn-block']) ?></p>
</div>


