<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_planner".
 *
 * @property integer $id
 * @property integer $recipe_id
 * @property string $date
 * @property string $timeofday
 *
 * @property Recipe $recipe
 * @property RecipePlannerIngredient[] $recipePlannerIngredients
 */
class RecipePlanner extends \yii\db\ActiveRecord {

  public $count;

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe_planner';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['recipe_id'], 'required'],
      [['recipe_id'], 'integer'],
      [['date'], 'safe'],
      [['timeofday'], 'string', 'max' => 25],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'recipe_id' => 'Recipe ID',
      'date' => 'Date',
      'timeofday' => 'What meal?',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipe() {
    return $this->hasOne(Recipe::className(), ['id' => 'recipe_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipePlannerIngredients() {
    return $this->hasMany(RecipePlannerIngredient::className(), ['recipe_planner_id' => 'id']);
  }
}
