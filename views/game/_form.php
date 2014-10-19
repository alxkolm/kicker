<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="game-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->beginField($model, 'date') ?>
        <?= Html::label('Дата') ?>
        <?= DatePicker::widget([
            'model'      => $model,
            'attribute'  => 'dateInput',
            'dateFormat' => 'dd.MM.yyyy'
        ]); ?>
    <?= $form->endField() ?>

    <fieldset class="form-group" style="border: 1px solid #bbb; padding: 20px;">
        <label>Комманда А</label>
        <?= $form->field($model, 'teamA_defender')->dropDownList(User::listData(), ['prompt' => '']) ?>
        <?= $form->field($model, 'teamA_forward')->dropDownList(User::listData(), ['prompt' => '']) ?>
    </fieldset>
    <fieldset class="form-group" style="border: 1px solid #bbb; padding: 20px;">
        <label>Комманда B</label>
        <?= $form->field($model, 'teamB_defender')->dropDownList(User::listData(), ['prompt' => '']) ?>

        <?= $form->field($model, 'teamB_forward')->dropDownList(User::listData(), ['prompt' => '']) ?>
    </fieldset>


    <?= $form->beginField($model, 'scoreA') ?>
    <?= Html::label('Cчет') ?>
    <?= Html::activeTextInput($model, 'scoreA', ['size' => 5, 'placeholder' => 'A']) ?>
    <?= Html::activeTextInput($model, 'scoreB', ['size' => 5, 'placeholder' => 'B']) ?>
    <?= $form->endField() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
