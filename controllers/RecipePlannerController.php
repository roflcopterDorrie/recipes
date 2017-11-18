<?php

namespace app\controllers;

use Yii;
use app\models\RecipePlanner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \app\models\RecipePlannerIngredient;

/**
 * RecipePlannerController implements the CRUD actions for RecipePlanner model.
 */
class RecipePlannerController extends Controller {

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
     * Lists all RecipePlanner models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => RecipePlanner::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RecipePlanner model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RecipePlanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($recipeId) {
        $model = new RecipePlanner();
        $model->recipe_id = $recipeId;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                // Create the ingredients for the shopping cart.
                $rpis = array();
                $ris = $model->getRecipe()->one()->getRecipeIngredients()->all();
                foreach ($ris as $ri) {
                    $rpi = new RecipePlannerIngredient();
                    $rpi->recipe_ingredient_id = $ri->id;
                    $rpi->recipe_planner_id = $model->id;
                    $rpis[] = $rpi;
                }

                if (RecipePlannerIngredient::validateMultiple($rpis)) {
                    foreach ($rpis as $rpi) {
                        $rpi->save();
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Could not validate all the ingredients for saving.');
                }

                return $this->redirect(['recipe/index']);
            }
        }


        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RecipePlanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        // Delete ingredients attached.
        $model = $this->findModel($id);
        RecipePlannerIngredient::deleteAll(['recipe_planner_id' => $model->id]);

        // Delete.
        $model->delete();

        return $this->redirect(['/recipe/index']);
    }

    /**
     * Finds the RecipePlanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecipePlanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RecipePlanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
