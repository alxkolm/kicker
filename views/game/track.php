<?php
/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-xs-3">
        <p>Комманда A</p>
        <p class="text-center"><?= \yii\helpers\Html::a($model->playerA->lastname, null, ['class' => 'btn btn-success'])?></p>

    </div>
    <div class="col-xs-3">
        <p class="text-right">Комманда A</p>
        <p class="text-center"><?= \yii\helpers\Html::a($model->playerB->lastname, null, ['class' => 'btn btn-success'])?></p>
    </div>
</div>
<div class="row" style="margin-top: 2em;">
    <div class="col-xs-3">
        <p class="text-center"><?= \yii\helpers\Html::a($model->playerC->lastname, null, ['class' => 'btn btn-primary'])?></p>
        <p>Комманда B</p>
    </div>
    <div class="col-xs-3">
        <p class="text-center"><?= \yii\helpers\Html::a($model->playerD->lastname, null, ['class' => 'btn btn-primary'])?></p>
        <p class="text-right">Комманда B</p>
    </div>
</div>
