<?php

namespace app\controllers;

use Yii;
use app\models\RecipePlannerIngredient;
use app\models\RecipePlanner;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecipePlannerIngredientController implements the CRUD actions for
 * RecipePlannerIngredient model.
 */
class ShoppingListController extends Controller {

  public function behaviors() {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
    ];
  }

  /**
   * Lists all RecipePlannerIngredient models.
   *
   * @return mixed
   */
  public function actionIndex() {

    $get = Yii::$app->request->get();
    if (isset($get['date'])) {
      $date = \DateTime::createFromFormat('Ymd', $get['date']);
    }
    else {
      $date = new \DateTime();
    }

    $week = $date->format('W');

    $day = $date->format('w');
    $date->sub(new \DateInterval('P' . $day . 'D'));
    $dates = [];
    for ($i = 0; $i < 7; $i++) {
      $date->add(new \DateInterval('P1D'));
      $dates[$date->format('l')] = $date->format('Y-m-d');
    }

    $prev = \DateTime::createFromFormat('Y-m-d', $dates['Monday']);
    $prev->sub(new \DateInterval('P7D'));

    $next = \DateTime::createFromFormat('Y-m-d', $dates['Monday']);
    $next->add(new \DateInterval('P7D'));


    $plannerIds = [];

    $planner = RecipePlanner::find()
      ->where([
          'between',
          'date',
          $dates['Monday'],
          $dates['Sunday'],
        ])
      ->all();

    foreach($planner as $plan) {
      $plannerIds[] = $plan->id;
    }

    $shoppingList = RecipePlannerIngredient::find()
      ->indexBy('id')
      ->where(['collected' => 0, 'recipe_planner_id' => $plannerIds])
      ->all();

    if (RecipePlannerIngredient::loadMultiple($shoppingList, Yii::$app->request->post()) && RecipePlannerIngredient::validateMultiple($shoppingList)) {
      foreach ($shoppingList as $item) {
        $item->save(FALSE);
      }
      $plannerIds = [];

      $planner = RecipePlanner::find()
        ->where([
          'between',
          'date',
          $dates['Monday'],
          $dates['Sunday'],
        ])
        ->all();

      foreach($planner as $plan) {
        $plannerIds[] = $plan->id;
      }

      $shoppingList = RecipePlannerIngredient::find()
        ->indexBy('id')
        ->where(['collected' => 0, 'recipe_planner_id' => $plannerIds])
        ->all();
    }
    return $this->render('update', [
      'shoppingList' => self::sortShoppingList($shoppingList),
      'dates' => $dates,
      'week' => $week,
      'prev' => $prev,
      'next' => $next,
    ]);
  }

  private function sortShoppingList($shoppingList) {
    $sortedList = [];
    foreach ($shoppingList as $index => &$item) {
      $item->ingredient = $item->getRecipeIngredient()->one();
      $item->section = $item->ingredient->getIngredientStoreSection()->one();
      if (!isset($item->section)) {
        $item->section = new \app\models\IngredientStoreSection();
        $item->section->name = 'None';
      }
      if (isset($sortedList[$item->section->name])) {
        $sortedList[$item->section->name][$index] = $item;
      }
      else {
        $sortedList[$item->section->name] = [$index => $item];
      }
    }
    return $sortedList;
  }

  /**
   * Finds the RecipePlannerIngredient model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   *
   * @param integer $id
   *
   * @return RecipePlannerIngredient the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id) {
    if (($model = RecipePlannerIngredient::findOne($id)) !== NULL) {
      return $model;
    }
    else {
      throw new NotFoundHttpException('The requested page does not exist.');
    }
  }

}
