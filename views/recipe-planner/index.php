<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipe Planners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-planner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>
      <?= Html::a('Prev', [
        '/recipe-planner/',
        'date' => $prev->format('Ymd'),
      ], ['class' => 'btn btn-primary']); ?>
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
      <?= Html::a('Next', [
        '/recipe-planner/',
        'date' => $next->format('Ymd'),
      ], ['class' => 'btn btn-primary']); ?>
    </h2>


    <div class="recipe-planner">

        <?php
        foreach ($dates as $day => $date) {

          echo '<div class="col-sm-12 col-md-4">';

          echo '<h3>' . $day . '</h3>';

          $date .= ' 00:00:00';

          $timeofday = ['Dinner'];
          foreach ($timeofday as $tod) {
            foreach ($planner as $plan) {
              if ($plan->date == $date && $plan->timeofday == $tod) {
                $recipe = $plan->getRecipe()->one();
                print $this->render('_mini_card', ['model' => $recipe]);
              } else {
                print 'Empty';
              }
            }
          }

          echo '</div>';
        }
        ?>

    </div>

</div>
