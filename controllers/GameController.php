<?php

namespace app\controllers;

use app\models\GameForm;
use app\models\GameUser;
use app\models\Goal;
use app\models\User;
use Yii;
use app\models\Game;
use app\models\GameSearch;
use yii\base\Action;
use yii\base\Exception;
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
                        'actions' => ['index', 'view', 'create', 'start'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'delete', 'track', 'goal', 'repeat'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => [$this, 'allowEdit']
                    ],
                    [
                        'actions' => ['undo-goal'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
            'model' => $this->findModel($id, GameForm::className()),
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
        $model = $this->findModel($id, GameForm::className());

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
     * @param string $modelClass
     * @return Game the loaded model
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $modelClass = '\app\models\Game')
    {
        if (($model = $modelClass::findOne($id)) !== null) {
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

        // Передаем данные об игроках в js
        $players = [
            'a' => $model->playerA->attributes,
            'b' => $model->playerB->attributes,
            'c' => $model->playerC->attributes,
            'd' => $model->playerD->attributes,
        ];

        // Передаем данные о js
        $game = $model->attributes;

        $kickerData = [
            'game'    => $game,
            'players' => $players,
            'goals'   => $goals
        ];

        $this->view->registerJs('var kickerTrack = '.Json::encode($kickerData).';', View::POS_HEAD);

        return $this->render('track2', [
            'model' => $model,
        ]);
    }

    public function actionGoal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $gameId = Yii::$app->request->post('id');
        $userId = Yii::$app->request->post('user');
        $autogoal = (bool)Yii::$app->request->post('autogoal');

        $game = $this->findModel($gameId);

        $goal = $game->scoreGoal($userId, $autogoal);
        $goal->refresh();

        return $goal->attributes;
    }

    public function actionUndoGoal()
    {
        /** @var Goal $model */
        $model = Goal::findOne(Yii::$app->request->getBodyParam('id'));
        if ($model === null){
            throw new Exception('Goal model not found');
        }

        $model->delete();
    }

    public function actionRepeat($id)
    {
        $model = $this->findModel($id);

        $game         = new Game();
        $game->scoreA = 0;
        $game->scoreB = 0;
        $game->date   = date('Y-m-d');
        if ($game->save()){
            foreach ($model->getGameUsers()->all() as $player){
                $newPlayer           = new GameUser();
                $newPlayer->game_id  = $game->id;
                $newPlayer->user_id  = $player->user_id;
                $newPlayer->team     = $player->team;
                $newPlayer->position = $player->position;
                $newPlayer->flags    = $player->flags;
                if (!$newPlayer->save()){
                    throw new Exception(json_encode($newPlayer->errors));
                }
            }
            $this->redirect(['game/track', 'id' => $game->id]);
        } else {
            throw new \Exception('Не удалось создать новую игру.'.Json::encode($game->errors));
        }

    }

    /**
     * @param $rule
     * @param $action Action
     * @return bool
     * @throws NotFoundHttpException
     */
    public function allowEdit($rule, $action)
    {
        $id = Yii::$app->request->getQueryParam('id', Yii::$app->request->getBodyParam('id'));
        $model = $this->findModel($id);
        return $model->userInGame();
    }
}
