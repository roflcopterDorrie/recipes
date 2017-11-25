<?php

use yii\helpers\Html;

?>
<div class="col-sm-6 col-md-4">

  <div class="card">

    <div class="card-image">
      <?php
      print Html::a(Html::img(\Yii::$app->imagemanager->getImagePath($model->ImageManager_image_id, 720, 405, "outbound")), ['/recipe/' . $model->id]);
      //print Html::a(Html::img('/images/placeHolder.png', ['width'=>360, 'height'=>202]), ['/recipe/' . $model->id]);
      ?>
    </div>

    <?php
    if ($model->popularity > 0) {
      ?>
      <div class="card-popularity"><?php
      for ($i = 0; $i < $model->popularity; $i++) {
        print '<i class="fa fa-fire" aria-hidden="true"></i>';
      }
      ?></div><?php
    }
    ?>

    <?php
    if ($model->rating) {
      ?>
      <div class="card-rating"><?php
      for ($i = 0; $i < $model->rating; $i++) {
        print '<i class="fa fa-star" aria-hidden="true"></i>';
      }
      for ($i = $model->rating; $i <= (5 - $model->rating); $i++) {
        print '<i class="fa fa-star-o" aria-hidden="true"></i>';
      }
      ?></div><?php
    }
    ?>

    <div class="card-summary">
      <?php
      print Html::a($model->name, ['/recipe/' . $model->id]);
      print Html::a('<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>', [
        'recipe-planner/create',
        'recipeId' => $model->id,
      ], ['class' => 'card-action']);
      ?>
    </div>

  </div>

</div>