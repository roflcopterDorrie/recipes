<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlannerIngredient */

$this->title = 'Shopping List';
$this->params['breadcrumbs'][] = 'Shopping List';
?>
<div class="recipe-planner-ingredient-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="recipe-planner-ingredient-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        foreach ($shoppingList as $section => $items) {
            echo "<h3>" . $section . "</h3>";
            foreach ($items as $index => $item) {
                echo '<div class="shopping-list-item">';
                echo $form->field($item, "[$index]collected")->widget(CheckboxX::classname(), ['pluginOptions' => ['threeState' => false, 'size' => 'lg']])->label(false);
                echo Html::label($item->ingredient->ingredient);
                echo '</div>';
            }
        }
        ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
