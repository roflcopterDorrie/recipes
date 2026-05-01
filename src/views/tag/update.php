<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */

$this->title = 'Update Tags: ' . ' ' . $model->tag;
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tag, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ingredient-store-section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
