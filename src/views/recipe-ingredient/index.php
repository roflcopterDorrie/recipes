<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\IngredientStoreSection;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipe Ingredients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-ingredient-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
    
    <h2>Ingredients</h2>
    <table id="ingredients">
        <tr><th>Ingredient</th><th>Store Section</th></tr>
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
            echo '</td></tr>';
        }
        ?>
    </table>
    
    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
