<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_planner_ingredient".
 *
 * @property integer $id
 * @property integer $recipe_ingredient_id
 * @property integer $collected
 * @property integer $recipe_planner_id
 *
 * @property RecipePlanner $recipePlanner
 * @property RecipeIngredient $recipeIngredient
 */
class RecipePlannerIngredient extends \yii\db\ActiveRecord {

  public $section;

  public $ingredient;

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe_planner_ingredient';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['recipe_ingredient_id', 'recipe_planner_id'], 'required'],
      [['recipe_ingredient_id', 'collected', 'recipe_planner_id'], 'integer'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'recipe_ingredient_id' => 'Recipe Ingredient ID',
      'collected' => 'Collected',
      'recipe_planner_id' => 'Recipe Planner ID',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipePlanner() {
    return $this->hasOne(RecipePlanner::className(), ['id' => 'recipe_planner_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipeIngredient() {
    return $this->hasOne(RecipeIngredient::className(), ['id' => 'recipe_ingredient_id']);
  }
}
