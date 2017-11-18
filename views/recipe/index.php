<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-index">

    <h2>Recipes</h2>
    
    <p>
        <?= Html::a('Create Recipe', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Recipe',
                'format' => 'raw',
                'value' => function ($data) {
                    $image = $data->getImage();
                    if ($image) {
                        return Html::img($image->getUrl('100x100')) . '<br/>' .Html::a($data->name, ['/recipe/' . $data->id]); 
                    }
                    return Html::a($data->name, ['/recipe/' . $data->id]);
                },
            ],
            [
                'label' => 'Plan',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a('Plan', ['recipe-planner/create', 'recipeId' => $data->id]);
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    
    <h2>
    <?= Html::a('Prev', ['/recipe/', 'date' => $prev->format('Ymd')], ['class' => 'btn btn-primary']); ?>
        <?php
        $now = new \DateTime();
        $thisWeek = $now->format('W');
        echo '<abbr title="' . DateTime::createFromFormat('Y-m-d', $dates['Monday'])->format('l jS F') ?> - <?= DateTime::createFromFormat('Y-m-d', $dates['Sunday'])->format('l jS F') . '">';
        if ($week == $thisWeek) {
            echo 'This week';
        } else if ($week+1 == $thisWeek) {
            echo 'Last week';
        } else if ($week-1 == $thisWeek) {
            echo 'Next week';
        } else {
            echo $week - $thisWeek . ' weeks away';
        }
        echo '</abbr>';
        ?>
    <?= Html::a('Next', ['/recipe/', 'date' => $next->format('Ymd')], ['class' => 'btn btn-primary']); ?>
    </h2>
    
    
    <div class="table-responsive">
        <table class="table">
        <?php
        $timeofday = ['Breakfast', 'Lunch', 'Dinner'];

        echo '<tr><th>Day</th>';
        foreach($timeofday as $tod) {
            echo '<th>' . $tod . '</th>';
        }
        echo '</tr>';

        foreach ($dates as $day => $date) {
            echo '<tr>';
            echo '<td>' . $day . '</td>';
            $date .= ' 00:00:00';

            foreach($timeofday as $tod) {
                echo '<td>';
                foreach ($planner as $plan) {
                    if ($plan->date == $date && $plan->timeofday == $tod) {
                        $recipe = $plan->getRecipe()->one();
                        $image = $recipe->getImage();
                        echo Html::img($image->getUrl('100x100'));
                        echo '<br/>';
                        echo Html::a($recipe->name, ['/recipe/' . $recipe->id]);
                    }
                }
                echo '</td>';
            }

            echo '</tr>';
        }
        ?>
        </table>
    </div>
</div>
