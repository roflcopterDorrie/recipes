<?php

namespace app\controllers;

use Yii;
use app\models\Recipe;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RecipePlannerIngredient;
use app\models\RecipeIngredient;
use app\models\RecipeQuick;
use app\models\RecipeStep;
use app\models\RecipePlanner;


/**
 * RecipeController implements the CRUD actions for Recipe model.
 */
class RecipeController extends Controller {

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
     * Lists all Recipe models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Recipe::find(),
            'pagination' => [
              'pageSize' => 1000,
            ],
        ]);

        return $this->render('index', [
          'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Recipe model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $ingredients = RecipeIngredient::find()->where(['recipe_id' => $id])->indexBy('id')->all();
        $steps = RecipeStep::find()->where(['recipe_id' => $id])->indexBy('id')->all();

        // Replace ingredients in steps.
        foreach ($steps as $step) {
            $matches = array();
            preg_match_all('/\[ingredient:\d+\]?/', $step->step, $matches);

            $matches = array_pop($matches);

            foreach ($matches as $match) {
                $split = explode(':', str_replace(']', '', $match));
                $ingId = $split[1];
                if (is_numeric($ingId)) {
                    $ingredient = RecipeIngredient::findOne($ingId);
                    $step->step = str_replace($match, '<span class="ingredient" style="color:blue">' . $ingredient->ingredient . '</span>', $step->step);
                }
            }
        }

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'ingredients' => $ingredients,
                    'steps' => $steps
        ]);
    }

    /**
     * Creates a new Recipe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new RecipeQuick();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Create recipe
            $recipe = new Recipe();
            $recipe->name = $model->name;
            $recipe->save();

            // Get image
            if ($model->image_url != null) {
                $recipe->attachImage($model->image_url);
            }

            // Create ingredients
            $ingredients = explode("\n", $model->ingredients);
            foreach ($ingredients as $i) {
                $ing = new RecipeIngredient();
                $ing->recipe_id = $recipe->id;
                $ing->ingredient = $i;
                $ing->save();
            }

            // Create steps
            $steps = explode("\n", $model->steps);
            foreach ($steps as $i) {
                $ing = new RecipeStep();
                $ing->recipe_id = $recipe->id;
                $ing->step = $i;
                $ing->save();
            }

            return $this->redirect(['view', 'id' => $recipe->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Recipe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        // Load ingredients.
        $ingredients = RecipeIngredient::find()->where(['recipe_id' => $id])->indexBy('id')->all();

        // Load ingredients.
        $steps = RecipeStep::find()->where(['recipe_id' => $id])->indexBy('id')->all();

        // Validate and save.
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->image != null) {
                $model->removeImages();
                $model->attachImage($model->image);
                $model->image = null;
                $model->save();
            }

            // Save ingredients.
            if (RecipeIngredient::loadMultiple($ingredients, Yii::$app->request->post(), 'RecipeIngredient')) {

                // Check for deletions.
                foreach ($ingredients as $delta => $ingredient) {
                    if ($ingredient->ingredient == null) {
                        $ingredient->delete();
                        unset($ingredients[$delta]);
                    }
                }

                // Save.
                if (RecipeIngredient::validateMultiple($ingredients)) {
                    foreach ($ingredients as $ingredient) {
                        $ingredient->save(false);
                    }
                }
            }

            // New ingredients.
            $newIngredients = Yii::$app->request->post('RecipeIngredient', []);
            if (isset($newIngredients['new'])) {
                foreach ($newIngredients['new'] as $ni) {
                    $ni['recipe_id'] = $id;
                    $ing = new RecipeIngredient();
                    $ing->setAttributes($ni);
                    if ($ing->validate()) {
                        $ing->save();
                    }
                }
            }

            // Reload ingredients.
            $ingredients = RecipeIngredient::find()->where(['recipe_id' => $id])->indexBy('id')->all();

            // Save steps.
            if (RecipeStep::loadMultiple($steps, Yii::$app->request->post(), 'RecipeStep')) {

                // Check for deletions.
                foreach ($steps as $delta => $ingredient) {
                    if ($ingredient->step == null) {
                        $ingredient->delete();
                        unset($steps[$delta]);
                    }
                }

                // Save.
                if (RecipeStep::validateMultiple($ingredients)) {
                    foreach ($steps as $ingredient) {
                        $ingredient->save(false);
                    }
                }
            }

            // New ingredients.
            $newIngredients = Yii::$app->request->post('RecipeStep', []);
            if (isset($newIngredients['new'])) {
                foreach ($newIngredients['new'] as $ni) {
                    $ni['recipe_id'] = $id;
                    $ing = new RecipeStep();
                    $ing->setAttributes($ni);
                    if ($ing->validate()) {
                        $ing->save();
                    }
                }
            }

            // Reload ingredients.
            $steps = RecipeStep::find()->where(['recipe_id' => $id])->indexBy('id')->all();

            // Load the form.
            return $this->render('update', [
                        'model' => $model,
                        'ingredients' => $ingredients,
                        'steps' => $steps
            ]);


            // Take us back to the view.
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {

            // Load the form.
            return $this->render('update', [
                        'model' => $model,
                        'ingredients' => $ingredients,
                        'steps' => $steps
            ]);
        }
    }

    /**
     * Deletes an existing Recipe model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);

        // Delete ingredients and planner ingredients.
        $ingredients = RecipeIngredient::find()->where(['recipe_id' => $id])->indexBy('id')->all();
        foreach ($ingredients as $ingredient) {
          RecipePlannerIngredient::deleteAll(['recipe_ingredient_id' => $ingredient->id]);
          $ingredient->delete();
        }

        RecipePlanner::deleteAll(['recipe_id' => $model->id]);
        RecipeStep::deleteAll(['recipe_id' => $model->id]);

        // Delete.
        $model->delete();

        return $this->redirect(['/recipe/index']);
    }

    /**
     * Finds the Recipe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recipe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Recipe::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
