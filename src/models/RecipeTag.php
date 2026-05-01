<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_tag".
 *
 * @property integer $id
 * @property integer $recipe_id
 * @property integer $tag_id
 */
class RecipeTag extends \yii\db\ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'recipe_tag';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['recipe_id', 'tag_id'], 'required'],
      [['recipe_id', 'tag_id'], 'integer'],
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getTag() {
    return $this->hasOne(Tag::class, ['id' => 'tag_id']);
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'recipe_id' => 'Recipe ID',
      'tag_id' => 'Tag ID',
    ];
  }
}
