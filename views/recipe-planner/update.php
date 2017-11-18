<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlanner */

$this->title = 'Update Recipe Planner: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Recipe Planners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recipe-planner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
