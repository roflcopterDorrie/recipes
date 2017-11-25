<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_step".
 *
 * @property integer $id
 * @property integer $recipe_id
 * @property string $step
 */
class RecipeStep extends \yii\db\ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe_step';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['recipe_id', 'step'], 'required'],
      [['recipe_id'], 'integer'],
      [['step'], 'string'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'recipe_id' => 'Recipe ID',
      'step' => 'Step',
    ];
  }
}
