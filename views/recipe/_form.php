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

<?php $this->registerJsFile(Url::to('/js/recipe.js'), ['depends' => [\yii\web\JqueryAsset::className()], 'position' => View::POS_END]); ?>

<div class="recipe-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php if (!$model->isNewRecord): ?>
        <?php $image = $model->getImage(); ?>
        <?php if ($image) : ?>
            <div>
                <?= Html::label('Existing image', null, ['class' => 'top']); ?>
                <?= Html::img($image->getUrl('100x100')); ?>    
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?= $form->field($model, 'image') ?>

    <h2>Ingredients</h2>
    <table id="ingredients">
        <tr><th>Ingredient</th><th>Store Section</th><th>Insert into step</th></tr>
        <?php
        $typeaheadIds = array();
        $storeSection = ArrayHelper::map(IngredientStoreSection::find()->orderBy('name')->all(), 'id', 'name');
        foreach ($ingredients as $index => $ingredient) {
            echo '<tr><td>';
            echo $form->field($ingredient, "[$index]ingredient")->textInput(['size' => '100'])->label(false);
            echo '</td><td>';
            echo $form->field($ingredient, "[$index]ingredient_store_section_id")->dropDownList(
                    $storeSection, array('prompt' => '-- Select --')
            )->label(false);
            echo '</td><td>';
            echo Html::button('+', ['class' => 'btn btn-primary insert-ingredient', 'data-ingredient' => $index]);
            echo '</td></tr>';
        }
        ?>
    </table>

    <div class="form-group">
        <?= Html::button('Add ingredient', ['class' => 'btn btn-success', 'id' => 'add-ingredient']) ?>
    </div>

    <h2>Steps</h2>
    <table id="steps">
        <?php
        $typeaheadIds = array();
        foreach ($steps as $index => $step) {
            echo '<tr><td>';
            echo $form->field($step, "[$index]step")->textArea(['rows' => '5', 'cols' => '150'])->label(false);
            echo '</td></tr>';
        }
        ?>
    </table>

    <div class="form-group">
        <?= Html::button('Add step', ['class' => 'btn btn-success', 'id' => 'add-step']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
