<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlannerIngredient */

$this->title = 'Shopping List';
$this->params['breadcrumbs'][] = 'Shopping List';
?>
<div class="recipe-planner-ingredient-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>
      <?= Html::a('<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>', [
        '/shopping-list/',
        'date' => $prev->format('Ymd'),
      ]); ?>
      <?php
      $now = new \DateTime();
      $thisWeek = $now->format('W');
      echo '<abbr title="' . DateTime::createFromFormat('Y-m-d', $dates['Monday'])
          ->format('l jS F') ?>
        - <?= DateTime::createFromFormat('Y-m-d', $dates['Sunday'])
        ->format('l jS F') . '">';
      if ($week == $thisWeek) {
        echo 'This week';
      }
      else {
        if ($week + 1 == $thisWeek) {
          echo 'Last week';
        }
        else {
          if ($week - 1 == $thisWeek) {
            echo 'Next week';
          }
          else {
            echo $week - $thisWeek . ' weeks away';
          }
        }
      }
      echo '</abbr>';
      ?>
      <?= Html::a('<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>', [
        '/shopping-list/',
        'date' => $next->format('Ymd'),
      ]); ?>
    </h2>


    <div class="recipe-planner-ingredient-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        foreach ($shoppingList as $section => $items) {
            echo "<h3>" . $section . "</h3>";
            foreach ($items as $index => $item) {
                echo '<div class="shopping-list-item">';
                echo $form->field($item, "[$index]collected")->widget(CheckboxX::classname(), ['pluginOptions' => ['threeState' => false, 'size' => 'lg']])->label(false);
                echo Html::label($item->ingredient->ingredient);
                echo '</div>';
            }
        }
        ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
