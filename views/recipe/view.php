<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Recipe */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Recipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->registerJsFile(Url::to('/js/recipe.js'), [
  'depends' => [\yii\web\JqueryAsset::className()],
  'position' => View::POS_END,
]); ?>

<div class="recipe-view">

    <div class="actions">
      <?= Html::a('<i class="fa fa-pencil icon-blue" aria-hidden="true"></i>', ['update', 'id' => $model->id]) ?>
      <?=
      Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', ['delete', 'id' => $model->id], [
        'data' => [
          'confirm' => 'Are you sure you want to delete this item?',
          'method' => 'post',
        ],
      ])
      ?>
      <?= Html::a('<i class="fa fa-play-circle" aria-hidden="true"></i>', ['#'], ['class' => 'button-cook-mode']) ?>
    </div>

    <h1><?= Html::encode($model->name) ?></h1>


    <div class="ingredients">
        <h2>Ingredients</h2>
        <?= Html::ul(ArrayHelper::map($ingredients, 'id', 'ingredient'));?>
    </div>

    <div class="steps">
        <h2>Steps</h2>
        <?= Html::ol(ArrayHelper::map($steps, 'id', 'step'), ['encode' => false]); ?>
    </div>

</div>
