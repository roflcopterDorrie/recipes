<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $count
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
            [['image'], 'safe']
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
            'image' => 'Replace Image - URL'
        ];
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

    public function getPopularity() {
        // Load all planner recipes.
        // SELECT recipe_id, count(recipe_id) AS count FROM `recipe_planner` GROUP BY recipe_id ORDER BY count DESC
        $most_popular = RecipePlanner::find()
          ->select(['COUNT(*) AS count', 'recipe_id'])
          ->groupBy(['recipe_id'])
          ->orderBy('count DESC')
          ->one();

        $recipe_popularity = RecipePlanner::find()
          ->select(['COUNT(*) AS count', 'recipe_id'])
          ->groupBy(['recipe_id'])
          ->where(['recipe_id'=>$this->id])
          ->orderBy(['count'=>'desc'])
          ->one();

        if (!isset($recipe_popularity)) {
          $recipe_popularity = new RecipePlanner();
          $recipe_popularity->count = 0;
        }
        $popularity = ($recipe_popularity->count / $most_popular->count) * 100;

        if ($popularity < 25) {
          return 0;
        } else if ($popularity < 50) {
          return 1;
        } else if ($popularity < 75) {
          return 2;
        } else {
          return 3;
        }
    }

    public function behaviors() {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

}
