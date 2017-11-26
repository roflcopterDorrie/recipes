<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://use.fontawesome.com/eceed754ce.js"></script>
  <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400"
          rel="stylesheet">

</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
  <?php
  NavBar::begin([
    'brandLabel' => 'Recipe Planner',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
      'class' => 'navbar-inverse navbar-fixed-top',
    ],
  ]);

  echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
      ['label' => 'Recipes', 'url' => ['/recipe/index']],
      ['label' => 'Planner', 'url' => ['/recipe-planner/index']],
      ['label' => 'Shopping List', 'url' => ['/shopping-list/index']],
      [
        'label' => 'Admin',
        'url' => '#',
        'items' => [
          [
            'label' => 'Store sections',
            'url' => ['ingredient-store-section/index'],
          ],
          [
            'label' => 'Assign ingredient to section',
            'url' => ['recipe-ingredient/index'],
          ],
        ],
      ],
      Yii::$app->user->isGuest ?
        ['label' => 'Login', 'url' => ['/auth/default/login']] :
        [
          'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
          'url' => ['/auth/default/logout'],
          'linkOptions' => ['data-method' => 'post'],
        ],
    ],
  ]);
  NavBar::end();
  ?>

    <div class="container content">
      <?php
      foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
      }
      ?>
      <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
