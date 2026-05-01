<?php

namespace app\models;

use Yii;
use \yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $tag
 */
class Tag extends \yii\db\ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'tag';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['tag'], 'required'],
      [['tag'], 'string', 'max' => 255],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'tag' => 'Tag',
    ];
  }

}
