<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-index">

    <div class="actions">
      <?= Html::a('<i class="fa fa-plus-circle" aria-hidden="true"></i>', ['create']) ?>
    </div>

    <h1>Recipes</h1>

    <?= $sort->link('popularity') . ' | ' . $sort->link('rating') . ' | ' . $sort->link('name'); ?>

    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'options' => [
            'class' => 'all-items item-list row',
        ],
        'itemView' => '_card',
    ]);
    ?>

</div>
