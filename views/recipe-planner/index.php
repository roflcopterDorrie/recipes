<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipe Planners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-planner-index">

    <h1>Planner</h1>

    <h2>
      <?= Html::a('<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>', [
        '/recipe-planner/',
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
        '/recipe-planner/',
        'date' => $next->format('Ymd'),
      ]); ?>
    </h2>


    <div class="recipe-planner col-sm-6 col-md-4">

        <?php
        foreach ($dates as $day => $date) {

          echo '<div class="recipe-planner-card">';

          echo '<h3>' . $day . '</h3>';

          $date .= ' 00:00:00';

          $timeofday = ['Dinner'];
          foreach ($timeofday as $tod) {
            foreach ($planner as $plan) {
              if ($plan->date == $date && $plan->timeofday == $tod) {
                $recipe = $plan->getRecipe()->one();
                print $this->render('_mini_card', ['model' => $recipe, 'recipePlanner' => $plan]);
              }
            }
          }

          echo '</div>';
        }
        ?>

    </div>

</div>
