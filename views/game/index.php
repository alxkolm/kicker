<?php

use app\models\Game;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Games';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Game', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'date',
            'scoreA',
            'scoreB',
            'playerA.fullname',
            'playerB.fullname',
            'playerC.fullname',
            'playerD.fullname',
            // 'modified',
            // 'created',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {track} {delete}',
                'headerOptions' => ['style' => 'width: 100px'],
                'buttons' => [
                    'track' => function ($url, Game $model, $key) {
                        return $model->userInGame(Yii::$app->user->id) ? Html::a('<span class="glyphicon glyphicon-flash"></span>', $url, [
                            'title' => Yii::t('yii', 'Track'),
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model->userInGame(Yii::$app->user->id) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '';
                    },
                    'update' => function ($url, $model, $key) {
                        return $model->userInGame(Yii::$app->user->id) ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]) : '';
                    },
                ],
            ],
        ],
    ]); ?>

</div>
