<?php

/* @var $this yii\web\View */
/* @var $model app\models\Game */
/* @var $form yii\widgets\ActiveForm */

use app\assets\TrackAppAsset;

TrackAppAsset::register($this);
$this->registerCss('html,body {background-color: black}');
?>
<div id="track-split-screen"></div>