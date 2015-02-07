<?php
namespace app\components\VKontakte;

use yii\base\Exception;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class VKontakteAuth
 *
 * @property array $accessData
 *
 * @package app\components\VKontakte
 */
class VKontakteAuth extends Object
{
    public $clientId            = 'your client id';
    public $secret              = 'secret key';
    public $apiVersion          = '5.28';
    public $redirectUri         = ['site/vkontakte'];
    public $authEndpoint        = 'https://oauth.vk.com/authorize';
    public $accessTokenEndpoint = 'https://oauth.vk.com/access_token';
    public $apiEndpoint         = 'https://api.vk.com/method/';
    public $lang                = 'ru';

    /**
     * @param array $params
     * @return string
     */
    public function authUrl(array $params = [])
    {
        $defaultParams = [
            'client_id'    => $this->clientId,
            'scope'        => 'email',
            'v'            => $this->apiVersion,
            'redirect_uri' => Url::to($this->redirectUri, true),
        ];

        $params = ArrayHelper::merge($defaultParams, $params);

        return $this->authEndpoint.'?'.http_build_query($params);
    }

    /**
     * Extract code from GET-parameters
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function code()
    {
        // First check for error
        if ( ($error = \Yii::$app->request->get('error')) !== null){
            throw new Exception('VKontakte: ' . $error . ': ' . \Yii::$app->request->get('error_description'));
        }

        return \Yii::$app->request->get('code');
    }

    /**
     * Get access token
     * @param $code
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function revealAccessToken($code)
    {
        $params = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->secret,
            'code'          => $code,
            'redirect_uri'  => Url::to($this->redirectUri, true),
        ];
        $url   = $this->accessTokenEndpoint . '?' . http_build_query($params);
        $reply = file_get_contents($url);
        $data  = Json::decode($reply);

        if (isset($data->error)){
            throw new Exception('VKontakte: ' . $data->error . ': ' . $data->error_description);
        }

        $this->setAccessData($data);

        return $data;
    }

    public function getAccessToken()
    {
        return isset($this->accessData['access_token']) ? $this->accessData['access_token'] : null;
    }

    public function getUserId()
    {
        return isset($this->accessData['user_id']) ? $this->accessData['user_id'] : null;
    }

    public function getEmail()
    {
        return isset($this->accessData['email']) ? $this->accessData['email'] : null;
    }

    public function methodUserGet($userId = null)
    {
        $userId = $userId ? $userId : $this->getUserId();
        $reply = $this->apiCall('users.get', ['user_id' => $userId]);
        if (is_array($reply) && count($reply) == 1){
            $reply = reset($reply);
        }
        return $reply;
    }

    public function apiCall($method, $params = [])
    {
        $params = ArrayHelper::merge(
            [
                'access_token' => $this->getAccessToken(),
                'v'            => $this->apiVersion,
                'lang'         => $this->lang,
            ],
            $params
        );
        $url = $this->apiEndpoint . $method . '?' . http_build_query($params);
        $reply = file_get_contents($url);

        $data = Json::decode($reply);

        if (isset($data['error'])){
            throw new Exception('VKontakte: code ' . $data['error']['error_code'] . '. ' . $data['error']['error_msg']. ' Original message: '.$reply);
        }

        return $data['response'];
    }

    public function setAccessData($value)
    {
        \Yii::$app->session->set('vkontakteAuthData', $value);
    }

    public function getAccessData()
    {
        $data = \Yii::$app->session->get('vkontakteAuthData');

        if ($data === null){
            $data = $this->revealAccessToken($this->code());
        }
        return $data;
    }
} 