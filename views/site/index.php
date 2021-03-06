<?php
/* @var $this yii\web\View */
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

$this->title = 'Kicker CRM';
?>
<div class="site-index">

    <div class="jumbotron">
        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['game/create'])?>">Записать результат</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= Url::to(['game/start'])?>">Начать игру</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Последние игры</h2>

                <div class="hidden-xs">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => new ActiveDataProvider(['query' => $recentGames]),
                        'itemView' => '_game',
                        'layout' => '{items}'
                    ]) ?>
                </div>
                <p><a class="btn btn-default" href="<?= Url::to(['game/index']) ?>">Все игры &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Лидеры</h2>

                <p>Здесь будет список лидеров.</p>

                <p><a class="btn btn-default" href="#">Полный рейтинг &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Чемпионаты</h2>

                <p>Здесь будет список чемпионатов.</p>

                <p><a class="btn btn-default" href="#">Все чемпионаты &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
