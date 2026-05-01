<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\IngredientStoreSection;
use app\models\Tag;
use app\models\RecipeTag;
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
        <thead>
          <tr>
              <th>Ingredient</th>
              <th>Store Section</th>
              <th>Insert into step</th>
          </tr>
        </thead>
        <tbody>
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
      </tbody>
    </table>

    <div class="form-group">
      <?= Html::button('Add ingredient', [
        'class' => 'btn btn-success',
        'id' => 'add-ingredient',
      ]) ?>
    </div>

    <h2>Steps</h2>
    <table id="steps">
      <tbody>
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
      </tbody>
    </table>

    <div class="form-group">
      <?= Html::button('Add step', [
        'class' => 'btn btn-success',
        'id' => 'add-step',
      ]) ?>
    </div>

    <h2>Tags</h2>

    <?php
      $availableTags = Tag::find()
        ->orderBy('tag')
        ->all();
      $plainTags = [];
      foreach($availableTags as $delta => $tag) {
        $plainTags[] = ["value" => $tag->tag, "id" => $tag->id];
      }
     
      echo "<script>var tags = " . json_encode($plainTags) . ";</script>";

      $currentTags = $model->recipeTags;
      $currentTagsTagify = [];
      foreach($currentTags as $delta => $recipeTag) {
        $currentTagsTagify[] = ["value" => $recipeTag->tag->tag, "id" => $recipeTag->tag->id];
      }
    ?>

    <input class="tagify--outside" name='tags' value='<?= json_encode($currentTagsTagify); ?>' autofocus>

    <div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>
