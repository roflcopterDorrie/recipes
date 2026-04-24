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

  <?= $form->field($model, 'rating')->dropDownList([
    1 => '1 star',
    2 => '2 star',
    3 => '3 star',
    4 => '4 star',
    5 => '5 star',
  ]) ?>

  <?= $form->field($model, 'ImageManager_image_id')
    ->widget(\noam148\imagemanager\components\ImageManagerInputWidget::className(), [
      'aspectRatio' => (16 / 9),
      //set the aspect ratio
      'cropViewMode' => 1,
      //crop mode, option info: https://github.com/fengyuanchen/cropper/#viewmode
      'showPreview' => TRUE,
      //false to hide the preview
      'showDeletePickedImageConfirm' => FALSE,
      //on true show warning before detach image
    ]); ?>

  <?= $form->field($model, 'ingredients')->textarea(['rows' => 6]) ?>

    Try to keep each step to under 240 characters.
  <?= $form->field($model, 'steps')->textarea(['rows' => 6]); ?>

    <div class="form-group">
      <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>
