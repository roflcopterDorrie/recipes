<?php

use yii\helpers\Html;

?>
<div class="card__mini">

    <div class="card__mini-image">
      <?= Html::a(Html::img(\Yii::$app->imagemanager->getImagePath($model->ImageManager_image_id, 720, 405, "outbound")), ['/recipe/' . $model->id]); ?>
    </div>

    <div class="card__mini-summary">
      <?= Html::a($model->name, ['/recipe/' . $model->id]); ?>
      <?= Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', [
        'delete',
        'id' => $recipePlanner->id,
      ], [
        'class' => 'card-action',
        'data' => [
          'confirm' => 'Are you sure you want to delete this item?',
          'method' => 'post',
        ],
      ]);
      ?>
    </div>

</div>