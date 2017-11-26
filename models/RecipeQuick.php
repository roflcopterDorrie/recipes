<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $rating
 * @property integer $ImageManager_image_id
 */
class RecipeQuick extends \yii\db\ActiveRecord {

  /**
   * @var UploadedFile|Null file attribute
   */
  public $ingredients;
  public $steps;
  public $rating;
  public $ImageManager_image_id;

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
      [['name', 'ingredients', 'steps', 'rating'], 'required'],
      [['ingredients', 'steps'], 'string'],
      [['ImageManager_image_id', 'rating'], 'integer'],
      [['name'], 'string', 'max' => 255],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'ImageManager_image_id' => 'Image',
      'rating' => 'Rating',
    ];
  }

}
