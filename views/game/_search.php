<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GameSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="game-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'teamA_playerA') ?>

    <?= $form->field($model, 'teamA_playerB') ?>

    <?= $form->field($model, 'teamB_playerC') ?>

    <?php // echo $form->field($model, 'teamB_playerD') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
