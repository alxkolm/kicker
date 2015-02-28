<?php
use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Kicker CRM',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => [
                    'class' => 'navbar-nav'
                ],
                'items' => [
                    ['label' => 'Записать результат', 'url' => ['game/create']],
                ],
            ]);
            $navLinks = [];
            if (Yii::$app->user->isGuest) {
                $navLinks[] = ['label' => 'Войти через ВК', 'url' => Yii::$app->vk->authUrl()];
                $navLinks[] = ['label' => 'Войти как мужик', 'url' => ['/site/login']];
            } else {
                $navLinks[] = ['label' => 'Выход (' . Yii::$app->user->identity->firstname . ' ' . Yii::$app->user->identity->lastname . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']];
            }

            echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $navLinks,
                ]);
                NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
