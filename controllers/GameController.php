<?php

namespace app\controllers;

use app\models\GameForm;
use app\models\User;
use Yii;
use app\models\Game;
use app\models\GameSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\View;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Game model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GameForm();
        $model->dateInput = date('d.m.Y');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Game model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Game model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GameForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStart()
    {
        $model = new GameForm();
        $model->scenario = 'track';
        $model->date = date('Y-m-d H:i:s');
        $model->scoreA = 0;
        $model->scoreB = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['game/track', 'id' => $model->id]);
        }

        return $this->render('start', [
            'model' => $model,
        ]);
    }

    public function actionTrack($id)
    {
        $model = $this->findModel($id);

        // Передаем все голы в js
        $goals = $model->getGoals()->orderBy('created')->all();
        $goals = ArrayHelper::getColumn($goals, 'attributes');
        $this->view->registerJs('var kicker_goals = '.Json::encode($goals).';', View::POS_HEAD);

        // Передаем данные об игроках в js
        $players = ArrayHelper::map(
            [
                $model->playerA,
                $model->playerB,
                $model->playerC,
                $model->playerD,
            ],
            'id',
            function ($a) use ($model) {return $a === null ? null : array_merge($a->attributes, ['team' => $model->isTeamA($a->id) ? 'A' : 'B']);}
            );
        $this->view->registerJs('var kicker_players = '.Json::encode($players).';', View::POS_HEAD);

        return $this->render('track', [
            'model' => $model,
        ]);
    }

    public function actionGoal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $gameId = Yii::$app->request->post('id');
        $userId = Yii::$app->request->post('user');
        $autogoal = (bool) Yii::$app->request->post('autogoal');

        $game = $this->findModel($gameId);

        $goal = $game->scoreGoal($userId, $autogoal);
        $goal->refresh();

        return $goal->attributes;
    }

    public function actionRepeat($id)
    {
        $model = $this->findModel($id);

        $game = new Game();
        $game->setAttributes($model->getAttributes([
            'teamA_playerA',
            'teamA_playerB',
            'teamB_playerC',
            'teamB_playerD',
            'playerA_role',
            'playerB_role',
            'playerC_role',
            'playerD_role',
        ]));
        $game->scoreA = 0;
        $game->scoreB = 0;
        $game->date = date('Y-m-d');
        if ($game->save()){
            $this->redirect(['game/track', 'id' => $game->id]);
        } else {
            throw new \Exception('Не удалось создать новую игру.'.Json::encode($game->errors));
        }

    }
}
