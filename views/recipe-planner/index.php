<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipe Planners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-planner-index row">

    <h1 class="col-sm-12">Planner</h1>

    <h2 class="col-sm-12">
      <?php
      $linkParams = [
        '/recipe-planner/',
        'date' => $prev->format('Ymd')
      ];
      if ($recipeId) {
        $linkParams['recipeId'] = $recipeId;
      }
      ?>


      <?= Html::a('<i class="fa fa-chevron-circle-left" aria-hidden="true"></i>', $linkParams); ?>
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

      $linkParams['date'] = $next->format('Ymd');
      ?>
      <?= Html::a('<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>', $linkParams); ?>
    </h2>


    <div class="recipe-planner col-sm-6 col-md-4">

        <?php
        foreach ($dates as $day => $date) {

          echo '<div class="recipe-planner-card">';

          echo '<h3 ' . (!$recipeId ? ' class="border"' : '') . '>' . $day . '</h3>';

          $found = FALSE;

          $date_full = $date . ' 00:00:00';

          $timeofday = ['Dinner'];
          foreach ($timeofday as $tod) {
            foreach ($planner as $plan) {
              if ($plan->date == $date_full && $plan->timeofday == $tod) {
                $recipe = $plan->getRecipe()->one();
                print $this->render('_mini_card', ['model' => $recipe, 'recipePlanner' => $plan]);
                $found = TRUE;
              }
            }
          }

          if (!$found && $recipeId) {
            print Html::a('<i class="fa fa-plus" aria-hidden="true"></i>',
              [
                'recipe-planner/create/',
                'recipeId' => $recipeId,
              ], [
                'data' => [
                  'method' => 'post',
                  'params' => [
                    'RecipePlanner[date]' => $date,
                    'RecipePlanner[timeofday]' => 'Dinner',
                    'RecipePlanner[recipeId]' => $recipeId,
                    'date' => $monday->format('Ymd')
                  ]
                ],
                'class' => 'empty-spot'
              ]
            );
          }
          echo '</div>';
        }
        ?>

    </div>

</div>