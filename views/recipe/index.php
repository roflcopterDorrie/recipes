<?php

use yii\helpers\Html;
use yii\widgets\ListView;

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
    ListView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'options' => [
            'class' => 'all-items item-list row',
        ],
        'itemView' => '_card',
    ]);
    ?>

</div>
