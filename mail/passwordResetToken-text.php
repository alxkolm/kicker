<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->firstname ?> <?= $user->lastname ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
