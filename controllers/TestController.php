<?php

namespace app\controllers;

use app\components\BaseApiController;
use app\components\Debug;
use yii\base\UserException;

class TestController extends BaseApiController
{
    public function actionClient($v = 100)
    {
        $v = intval($v);

        $url = "/client/$v/";
        $login_url = $url . 'user/login';
        $action_list = null;

        if ($v == 100) {
            $action_list = \app\modules\client\v100\data\Api::ActionList();
        }

        if (!$action_list) {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item) {
            $type = $item['type'];
            if (!in_array($type, $type_list)) {
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

        if ($v == 100) {
            $action_list = \app\modules\admin\v100\data\Api::ActionList();
        }

        if (!$action_list) {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item) {
            $type = $item['type'];
            if (!in_array($type, $type_list)) {
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

    public function actionDebugList()
    {
        Debug::getDebugList();
    }

    public function actionTest()
    {
       $url = "http://www.kuyoo.com/tws/gamesearch/search?Filter=&Path=0,242375-0,708913-0,708915-0,303844-43392,3-43393,1-43394,1&PageNum=1&PageSize=100&KeyWord=&OrderStyle=6&Property=0&callback=getMyItemsListCallback&dtag=874&g_tk=1046278908&g_ty=ls";
     
       $res = curl("GET", $url);
     
       if (isset($res['response']) && $res['response'])
       {
           $response = $res['response'];
           
           $response = str_replace('getMyItemsListCallback(', "", $response);
           $response = str_replace(');', "", $response);
           $response = str_replace('/*  |xGv00|478769a2bd91fc79acb93945fbb428ad */', "", $response);
           $response = str_replace('/*  |xGv00|f8fcf3b9fde3fb5dceb9d6608729e558 */', "", $response);
           
           
           dump($response);
           dump(json_decode($response));
           
           
       }
    }

}