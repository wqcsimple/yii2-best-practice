<?php

namespace app\controllers;

use app\components\BaseApiController;
use app\components\Debug;
use app\components\DXUtil;
use app\components\WxNotify;
use dix\base\component\Redis;
use GuzzleHttp\Client;
use yii\base\UserException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
    }

    function jsonp_decode($jsonp, $assoc = false)
    {
        if ($jsonp[0] !== '[' && $jsonp[0] !== '{') {
            $jsonp = substr($jsonp, strpos($jsonp, '('));
        }

        $jsonp = trim($jsonp, '();');
        $jsonp = str_replace(");", "", $jsonp);

        $pattern = "/(\/\*.*\*\/)/";
        preg_match_all($pattern, $jsonp, $out);
        if (sizeof($out) > 0) {
            $arr1 = array_unique($out[0]);

            foreach ($arr1 as $kye => $v) {
                $jsonp = str_replace($v, "", $jsonp);
            }

//            return json_decode($jsonp, $assoc);
            return $jsonp;
        }

        return "";
    }

    function yang_gbk2utf8($str)
    {
        $charset = mb_detect_encoding($str, array('UTF-8', 'GBK', 'GB2312'));
        $charset = strtolower($charset);
        if ('cp936' == $charset) {
            $charset = 'GBK';
        }
        if ("utf-8" != $charset) {
            $str = iconv($charset, "UTF-8//IGNORE", $str);
        }
        return $str;
    }


}