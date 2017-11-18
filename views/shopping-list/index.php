<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlannerIngredient */

$this->title = 'Shopping List';
$this->params['breadcrumbs'][] = ['label' => 'Shopping List', 'url' => ['index']];
?>
<div class="recipe-planner-ingredient-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
