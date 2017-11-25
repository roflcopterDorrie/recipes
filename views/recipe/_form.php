<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\IngredientStoreSection;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Recipe */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $this->registerJsFile(Url::to('/js/recipe.js'), [
  'depends' => [\yii\web\JqueryAsset::className()],
  'position' => View::POS_END,
]); ?>

<div class="recipe-form">

  <?php $form = ActiveForm::begin([
    'enableClientValidation' => FALSE,
    'options' => ['enctype' => 'multipart/form-data'],
  ]); ?>

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

    <h2>Ingredients</h2>
    <table id="ingredients">
        <tr>
            <th>Ingredient</th>
            <th>Store Section</th>
            <th>Insert into step</th>
        </tr>
      <?php
      $typeaheadIds = [];
      $storeSection = ArrayHelper::map(IngredientStoreSection::find()
        ->orderBy('name')
        ->all(), 'id', 'name');
      foreach ($ingredients as $index => $ingredient) {
        echo '<tr><td>';
        echo $form->field($ingredient, "[$index]ingredient")
          ->textInput(['size' => '100'])
          ->label(FALSE);
        echo '</td><td>';
        echo $form->field($ingredient, "[$index]ingredient_store_section_id")
          ->dropDownList(
            $storeSection, ['prompt' => '-- Select --']
          )
          ->label(FALSE);
        echo '</td><td>';
        echo Html::button('+', [
          'class' => 'btn btn-primary insert-ingredient',
          'data-ingredient' => $index,
        ]);
        echo '</td></tr>';
      }
      ?>
    </table>

    <div class="form-group">
      <?= Html::button('Add ingredient', [
        'class' => 'btn btn-success',
        'id' => 'add-ingredient',
      ]) ?>
    </div>

    <h2>Steps</h2>
    <table id="steps">
      <?php
      $typeaheadIds = [];
      foreach ($steps as $index => $step) {
        echo '<tr><td>';
        echo $form->field($step, "[$index]step")->textArea([
          'rows' => '5',
          'cols' => '150',
        ])->label(FALSE);
        echo '</td></tr>';
      }
      ?>
    </table>

    <div class="form-group">
      <?= Html::button('Add step', [
        'class' => 'btn btn-success',
        'id' => 'add-step',
      ]) ?>
    </div>

    <div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>
