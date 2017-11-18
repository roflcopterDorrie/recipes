<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RecipePlanner */

$this->title = 'Create Recipe Planner';
$this->params['breadcrumbs'][] = ['label' => 'Recipe Planners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-planner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
