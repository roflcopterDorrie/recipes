<?php

use yii\helpers\Html;

?>
<div class="col-sm-4">

    <div class="card">

        <div class="card-image">
          <?php
          $image = $model->getImage();
          if ($image) {
            print Html::a(Html::img($image->getUrl('350x200')), ['/recipe/' . $model->id]);
          } else {
            print Html::a(Html::img('/images/placeHolder.png'), ['/recipe/' . $model->id]);
          }

          ?>
        </div>


          <?php
          if ($model->popularity > 0) {
            ?><div class="card-popularity"><?php
            for ($i = 0; $i < $model->popularity; $i++) {
              print '<i class="fa fa-fire popularity_flame" aria-hidden="true"></i>';
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
          ], ['class'=>'card-action']);
          ?>
        </div>

    </div>

</div>