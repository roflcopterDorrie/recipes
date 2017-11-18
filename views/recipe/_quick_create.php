<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\IngredientStoreSection;

/* @var $this yii\web\View */
/* @var $model app\models\Recipe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recipe-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'ingredients')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'steps')->textarea(['rows' => 6]) ; ?>
    
    <?= $form->field($model, 'image_url') ?>
    
    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
