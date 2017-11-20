<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $popularity
 *
 * @property RecipeIngredient[] $recipeIngredients
 * @property RecipePlanner[] $recipePlanners
 */
class Recipe extends \yii\db\ActiveRecord {

    public $image;

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
            [['name'], 'required'],
            [['description', 'image'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['image', 'popularity'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Replace Image - URL',
            'popularity' => 'Popularity'
        ];
    }

    public function attributes() {
      return array_merge(parent::attributes(), ['popularity']);
    }

    public function sortModels($models, $sort) {

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
     * setup search function for filtering and sorting
     * based on `orderAmount` field
     */
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
ORDER BY count DESC LIMIT 1) * 100 / 25) AS popularity")
        ->orderBy($params['sort']->orders);

      $params['query'] = $query;

      $dataProvider = new ActiveDataProvider($params);

      /**
       * Setup your sorting attributes
       * Note: This is setup before the $this->load($params)
       * statement below
       */
      $dataProvider->setSort([
        'attributes' => [
          'popularity',
        ]
      ]);

      return $dataProvider;
    }

    public function behaviors() {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

}
