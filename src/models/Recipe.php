<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property integer $rating
 * @property integer $popularity
 *
 * @property RecipeIngredient[] $recipeIngredients
 * @property RecipeTag[] $recipeTags
 * @property RecipePlanner[] $recipePlanners
 */
class Recipe extends \yii\db\ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['name', 'rating'], 'required'],
      [['name'], 'string', 'max' => 255],
      [['ImageManager_image_id', 'popularity'], 'safe'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'image' => 'Replace Image - URL',
      'popularity' => 'Popularity',
      'ImageManager_image_id' => 'Image id',
    ];
  }

  public function attributes() {
    return array_merge(parent::attributes(), ['popularity']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipeIngredients() {
    return $this->hasMany(RecipeIngredient::className(), ['recipe_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipePlanners() {
    return $this->hasMany(RecipePlanner::className(), ['recipe_id' => 'id']);
  }

   /**
   * @return \yii\db\ActiveQuery
   */
  public function getRecipeTags() {
    return $this->hasMany(RecipeTag::class, ['recipe_id' => 'id']);
  }

  public function getTagNames() {
    $recipeTags = $this->hasMany(RecipeTag::className(), ['recipe_id' => 'id'])->all();
    return ArrayHelper::getColumn($recipeTags, function ($element) {
      return $element->tag;
    });
  }
}
