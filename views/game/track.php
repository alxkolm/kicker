<?php

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */

use app\assets\TrackAppAsset;

TrackAppAsset::register($this);
?>
<div id="container-track">
    <div class="row">
        <div class="col-xs-3">
            <p class="text-center">Комманда A</p>
            <p class="text-center">
                <?= \yii\helpers\Html::a($model->playerA->lastname, null, ['class' => 'btn btn-success do-goal', 'user-id' => $model->playerA->id, 'game-id' => $model->id])?>
                <?= \yii\helpers\Html::a('Автогол', null, ['class' => 'btn btn-danger btn-xs do-goal autogoal', 'user-id' => $model->playerA->id, 'game-id' => $model->id])?>
            </p>


        </div>
        <div class="col-xs-3">
            <p class="text-center">Комманда B</p>
            <p class="text-center">
                <?= \yii\helpers\Html::a($model->playerC->lastname, null, ['class' => 'btn btn-primary do-goal', 'user-id' => $model->playerC->id, 'game-id' => $model->id])?>
                <?= \yii\helpers\Html::a('Автогол', null, ['class' => 'btn btn-danger btn-xs do-goal autogoal', 'user-id' => $model->playerC->id, 'game-id' => $model->id])?>
            </p>
        </div>
    </div>
    <div class="row" style="margin-top: 2em;">
        <div class="col-xs-3">

            <p class="text-center">
                <?= \yii\helpers\Html::a($model->playerB->lastname, null, ['class' => 'btn btn-success do-goal', 'user-id' => $model->playerB->id, 'game-id' => $model->id])?>
                <?= \yii\helpers\Html::a('Автогол', null, ['class' => 'btn btn-danger btn-xs do-goal autogoal', 'user-id' => $model->playerB->id, 'game-id' => $model->id])?>
            </p>
        </div>
        <div class="col-xs-3">
            <p class="text-center">
                <?= \yii\helpers\Html::a($model->playerD->lastname, null, ['class' => 'btn btn-primary do-goal', 'user-id' => $model->playerD->id, 'game-id' => $model->id])?>
                <?= \yii\helpers\Html::a('Автогол', null, ['class' => 'btn btn-danger btn-xs do-goal autogoal', 'user-id' => $model->playerD->id, 'game-id' => $model->id])?>
            </p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-md-offset-1 col-xs-6 col-xs-offset-0 events" id="container-events">
    </div>
</div>
