<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Начать игру';
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <h1><?= Html::encode($this->title) ?></h1>
        <div class="game-form">

            <?php $form = ActiveForm::begin(); ?>
            <?=$form->errorSummary($model) ?>

            <fieldset class="form-group" style="border: 1px solid #bbb; padding: 20px;">
                <label>Комманда А</label>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'teamA_playerA')->dropDownList(User::listData(), ['prompt' => '']) ?>
                        <?= $form->field($model, 'playerA_role_form')->checkboxList([1 => 'Нападение', 2 => 'Защита'])?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'teamA_playerB')->dropDownList(User::listData(), ['prompt' => '']) ?>
                        <?= $form->field($model, 'playerB_role_form')->checkboxList([1 => 'Нападение', 2 => 'Защита'])?>
                    </div>
                </div>


            </fieldset>
            <fieldset class="form-group" style="border: 1px solid #bbb; padding: 20px;">
                <label>Комманда B</label>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'teamB_playerC')->dropDownList(User::listData(), ['prompt' => '']) ?>
                        <?= $form->field($model, 'playerC_role_form')->checkboxList([1 => 'Нападение', 2 => 'Защита'])?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'teamB_playerD')->dropDownList(User::listData(), ['prompt' => '']) ?>
                        <?= $form->field($model, 'playerD_role_form')->checkboxList([1 => 'Нападение', 2 => 'Защита'])?>
                    </div>
                </div>



            </fieldset>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
</div>