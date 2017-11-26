<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlanner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recipe-planner-form">

  <?php $form = ActiveForm::begin(); ?>

  <?= $form->field($model, 'recipe_id')->hiddenInput()->label(FALSE); ?>

  <?php
  echo DatePicker::widget([
    'name' => 'RecipePlanner[date]',
    'options' => ['placeholder' => 'Select cooking date'],
    'pluginOptions' => [
      'todayHighlight' => TRUE,
      'format' => 'yyyy-mm-dd',
      'autoclose' => TRUE,
    ],
  ]);
  echo $form->field($model, "timeofday")->dropDownList(['Dinner' => 'Dinner']);
  ?>

    <div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>




