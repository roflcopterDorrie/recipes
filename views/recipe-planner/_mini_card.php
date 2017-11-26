<?php

use yii\helpers\Html;

?>
    <div class="card__mini">

        <div class="card__mini-image">
          <?= Html::a(Html::img(\Yii::$app->imagemanager->getImagePath($model->ImageManager_image_id, 720, 405, "outbound")), ['/recipe/' . $model->id]); ?>
        </div>

        <div class="card__mini-summary">
          <?= Html::a($model->name, ['/recipe/' . $model->id]); ?>
        </div>

    </div>