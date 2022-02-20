<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->username;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode('Usuario (dni): ' . $model->username) ?></h1>

    <div class="row">
        <div class="col-sm">
            <?= Html::a(Yii::t('app', 'Actualizar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-lg btn-block']) ?>
        </div>
        <div class="col-sm">
            <?= Html::a(Yii::t('app', 'Eliminar Usuario'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-lg btn-block',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status == '10') {
                        return 'Activo';
                    } else if ($model->status == '9') {
                        return 'Inactivo';
                    } else {
                        return 'Dado de baja';
                    }
                }
            ],
            'created_at',
            'updated_at',
            //'verification_token',
        ],
    ]) ?>

</div>
