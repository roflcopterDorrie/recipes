<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\IngredientStoreSection */

$this->title = 'Create Ingredient Store Section';
$this->params['breadcrumbs'][] = ['label' => 'Ingredient Store Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredient-store-section-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
