<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Recipe */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Recipes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-view">

    <h1><?= Html::encode($model->name) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?php
    /*$image = $model->getImage();
    if ($image) {
        echo '<div style="width: 500px">';
        echo Html::img('/' . $image->getPath('500x300'));
        echo '</div>';
    }*/
    ?>
    <h2>Ingredients</h2>
    <?= Html::ul(ArrayHelper::map($ingredients, 'id', 'ingredient'));?>
    
    <h2>Steps</h2>
    <?= Html::ol(ArrayHelper::map($steps, 'id', 'step'), ['encode' => false]); ?>

</div>
