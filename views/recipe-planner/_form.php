<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\RecipePlanner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recipe-planner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'recipe_id')->hiddenInput()->label(false); ?>

    <?php
    echo DatePicker::widget([
        'name' => 'RecipePlanner[date]',
        'options' => ['placeholder' => 'Select cooking date'],
        'pluginOptions' => [
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
        ]
    ]);
    
    $timeofday = ['Breakfast' => 'Breakfast', 'Lunch' => 'Lunch', 'Dinner' => 'Dinner'];
    echo $form->field($model, "timeofday")->dropDownList(
                $timeofday, array('prompt' => '-- Select --')
        );
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




