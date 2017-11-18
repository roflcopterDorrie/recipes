<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
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

    public function behaviors() {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

}
