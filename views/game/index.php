<?php

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
                'buttons' => [
                    'track' => function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-flash"></span>', $url, [
                            'title' => Yii::t('yii', 'Track'),
                            'data-pjax' => '0',
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>

</div>
