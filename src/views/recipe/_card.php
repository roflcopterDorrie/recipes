<?php

use yii\helpers\Html;

?>
<div class="col-sm-6 col-md-4">

  <div class="card">

    <div class="card-image">
      <?= Html::a(Html::img(\Yii::$app->imagemanager->getImagePath($model->ImageManager_image_id, 720, 405, "outbound")), ['/recipe/' . $model->id]);?>
    </div>

    <div class="card-tags">
      
      <?php 
      foreach($model->getTagNames() as $tag) {
        echo "<span class='card-tag'>";
        echo $tag->tag;
        echo "</span>";
      }
      ?>
    </div>

    <div class="card-summary">
      <?php
      print Html::a($model->name, ['/recipe/' . $model->id]);
      print Html::a('<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>', [
        'recipe-planner/index',
        'recipeId' => $model->id,
      ], ['class' => 'card-action']);
      ?>
    </div>

  </div>

</div>