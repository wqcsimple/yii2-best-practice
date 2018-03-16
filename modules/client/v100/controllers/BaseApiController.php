<?php

namespace app\modules\client\v100\controllers;

use app\components\Debug;
use dix\base\component\DXUtil;
use dix\base\component\Redis;
use dix\base\exception\ServiceException;
use app\modules\client\v100\services\ClientApiService;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\base\UserException;


class BaseApiController extends Controller
{
    public $params;

    public $user_id;
    public $token;

    public $redis;

    public function beforeAction($action)
    {
        $ok = parent::beforeAction($action);
        
        header("Access-Control-Allow-Origin: *");

        foreach ($_REQUEST as $key => $value)
        {
            $this->params[$key] = $value;
        }
        
        //verify token, update token time and get user_id
//        $this->user_id = ClientApiService::before($this->params, $this->action->id, $this->action->controller->id);
//        if (isset($params['token']))
//        {
//            $this->token = $params['token'];
//        }

        if ($this->user_id)
        {
            ClientApiService::doUserStat($this->user_id);
        }

        return $ok;
    }

    public function actionError()
    {
        http_response_code(200);

        if (($exception = Yii::$app->getErrorHandler()->exception) === null)
        {
        }

        if ($exception instanceof ServiceException)
        {
            $this->finishError($exception->getCode(), $exception->getMessage(), $exception->getData());
        }

        $code = $exception instanceof HttpException ? $exception->statusCode : $exception->getCode();

        $message = $exception instanceof UserException ? $exception->getMessage() : 'An internal server error occurred';

        if ($code == 404)
        {
            $message = 'Not found';
        }

        $this->finish(['code' => $code, 'error' => $message]);
    }

    public function checkParams($requiredParams)
    {
        ClientApiService::checkParams($this->params, $requiredParams);
    }

    public function finishError($code, $message, $extra = [])
    {
        $data['code'] = intval($code);
        $data['message'] = $message;
        if (is_array($extra))
        {
            $data = array_merge($data, $extra);
        }

        $this->finish($data);
    }

    public function finishSuccess($success)
    {
        $data['code'] = 0;
        if ($success)
        {
            $data['data'] = $success;
        }
        $this->finish($data);
    }

    public function finish($data)
    {
        DXUtil::doActionStat($this->route);
        header('Content-type:application/json;charset=UTF-8');
        die(json_encode($data));
    }

    public function redis()
    {
        return Redis::client();
    }

    public function log($key, $data)
    {
        Debug::log($key, $data);
    }

 

}
