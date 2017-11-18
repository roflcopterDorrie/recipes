<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredient_store_section".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Ingredient[] $ingredients
 */
class IngredientStoreSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ingredient_store_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::className(), ['ingredient_store_section_id' => 'id']);
    }
}
