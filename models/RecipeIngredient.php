<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_ingredient".
 *
 * @property integer $id
 * @property integer $recipe_id
 * @property string $ingredient
 * @property integer $ingredient_store_section_id
 *
 * @property IngredientStoreSection $ingredientStoreSection
 */
class RecipeIngredient extends \yii\db\ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe_ingredient';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['recipe_id', 'ingredient'], 'required'],
      [['recipe_id', 'ingredient_store_section_id'], 'integer'],
      [['ingredient'], 'string'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'recipe_id' => 'Recipe ID',
      'ingredient' => 'Ingredient',
      'ingredient_store_section_id' => 'Ingredient Store Section ID',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getIngredientStoreSection() {
    return $this->hasOne(IngredientStoreSection::className(), ['id' => 'ingredient_store_section_id']);
  }
}
