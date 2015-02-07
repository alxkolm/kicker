<?php

namespace app\controllers;

use app\models\SocialIdentity;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionVkontakte()
    {
        // Пытаемся найти пользователя
        $identity = SocialIdentity::findOne(['network_user_id' => Yii::$app->vk->getUserId()]);
        if ($identity === null) {
            $transaction = Yii::$app->db->beginTransaction();
            // Создаем нового пользователя
            $userData = Yii::$app->vk->methodUserGet();
            $user = new User();
            $user->setAttributes(
                [
                    'lastname' => $userData['last_name'],
                    'firstname' => $userData['first_name'],
                ]
            );
            if ($user->save()){
                $identity = new SocialIdentity();
                $identity->user_id = $user->id;
                $identity->network = 'vkontakte';
                $identity->network_user_id = (string)$userData['id'];
                if (!$identity->save()){
                    throw new Exception('Could not create identity. '.Json::encode($identity->errors));
                }
            } else {
                throw new Exception('Could not create user. '.Json::encode($user->errors));
            }
            $transaction->commit();
        } else {
            $user = User::findOne($identity->user_id);
        }

        return "Привет {$user->firstname} {$user->lastname}.";
    }
}
