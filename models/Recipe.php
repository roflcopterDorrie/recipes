<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property integer $rating
 * @property integer $popularity
 *
 * @property RecipeIngredient[] $recipeIngredients
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

  public function search($params) {
    $query = $this->find()
      ->select("*, id AS tmp_recipe_id,
          FLOOR((SELECT COUNT(recipe_id) AS count FROM recipe
          LEFT JOIN recipe_planner ON recipe.id = recipe_planner.recipe_id
          WHERE recipe.id = tmp_recipe_id
          GROUP BY recipe.id
          ORDER BY count DESC LIMIT 1)
          /
          (SELECT COUNT(recipe_id) AS count FROM recipe
          LEFT JOIN recipe_planner ON recipe.id = recipe_planner.recipe_id
          GROUP BY recipe.id
          ORDER BY count DESC LIMIT 1) * 100 / 33) AS popularity")
      ->orderBy($params['sort']->orders);

    unset($params['sort']);
    $params['query'] = $query;

    $dataProvider = new ActiveDataProvider($params);

    return $dataProvider;
  }

  public function getRating() {
    if ($this->rating == NULL) {
      $this->rating = 0;
    }
    return $this->rating;
  }

}
