<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlannerIngredient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recipe-planner-ingredient-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'recipe_ingredient_id')->textInput() ?>

    <?= $form->field($model, 'collected')->textInput() ?>

    <?= $form->field($model, 'recipe_planner_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
