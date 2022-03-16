<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Usuarios');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-sm">
            <?= Html::a(Yii::t('app', 'Crear nuevo Usuario'), ['create'], ['class' => 'btn btn-success btn-lg btn-block']) ?>
        </div>
        <div class="col-sm">
            <?= Html::a(Yii::t('app', 'Tareas Administrador'), ['/admin'], ['class' => 'btn btn-danger btn-lg btn-block']) ?>
        </div>
    </div>
    <br><br>
        

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->status == '9'){
                return ['style' => 'background-color: #ff9999'];
            }
            else if ($model->status == '10')//ff4040
            {
                return ['style' => 'background-color: #99ff99'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            //'created_at',
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                // 'urlCreator' => function ($action, User $model, $key, $index, $column) {
                //     return Url::toRoute([$action, 'id' => $model->id]);
                //  }
            ],
        ],
    ]); ?>


</div>

<style type="text/css">
    
</style>
