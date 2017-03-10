<?php

namespace app\controllers;

use app\components\Debug;
use app\modules\client\v101\data\Api;
use app\modules\client\v101\services\TaskService;
use app\services\WebSocketService;
use Yii;
use yii\base\UserException;
use yii\helpers\Html;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionClient($v = 100)
    {
        $v = intval($v);

        $url = "/client/$v/";
        $login_url = $url . 'user/login';
        $action_list = null;

        if ($v == 100)
        {
            $action_list = \app\modules\client\v100\data\Api::ActionList();
        }

        if (!$action_list)
        {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item)
        {
            $type = $item['type'];
            if (!in_array($type, $type_list))
            {
                $type_list[] = $type;
            }
        }

        $cookie_key_prefix = 'client';

        return $this->render('/test/api', [
            'cookie_key_prefix' => $cookie_key_prefix,
            'url' => $url,
            'login_url' => $login_url,
            'action_list' => $action_list,
            'type_list' => $type_list
        ]);
    }

    public function actionAdmin($v = 100)
    {
        $v = intval($v);

        $url = "/admin/$v/";
        $login_url = $url . 'admin/login';
        $action_list = null;

        if ($v == 100)
        {
            $action_list = \app\modules\admin\v100\data\Api::ActionList();
        }

        if (!$action_list)
        {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item)
        {
            $type = $item['type'];
            if (!in_array($type, $type_list))
            {
                $type_list[] = $type;
            }
        }

        $cookie_key_prefix = 'admin';

        return $this->render('/test/api', [
            'cookie_key_prefix' => $cookie_key_prefix,
            'url' => $url,
            'login_url' => $login_url,
            'action_list' => $action_list,
            'type_list' => $type_list
        ]);
    }
    
    public function actionDebugList() {
        Debug::getDebugList();
    }
    
    public function actionTest()
    {
        set_time_limit(0);
       $url = "http://qinggan.jiayuan.com/zhuanti/xingge/show.php?uname=%E5%92%A9%E5%92%A9";
       
       for ($a = 1; $a < 999; $a++)
       {
           sleep(3);
           $response = curl("GET", $url);
       }
    }
    
}