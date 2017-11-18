<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RecipePlannerIngredient */

$this->title = 'Create Recipe Planner Ingredient';
$this->params['breadcrumbs'][] = ['label' => 'Recipe Planner Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-planner-ingredient-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
