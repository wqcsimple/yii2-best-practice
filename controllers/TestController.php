<?php

namespace app\controllers;

use app\components\BaseApiController;
use app\components\Debug;
use app\components\DXUtil;
use app\components\WxNotify;
use app\models\Admin;
use app\models\User;
use dix\base\component\Redis;
use dix\base\module\generator\api\ApiGenerator;
use GuzzleHttp\Client;
use yii\base\UserException;
use yii\db\Query;
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

    function doHttpPost($url, $params)
    {
        $curl = curl_init();

        $response = false;
        do
        {
            // 1. 设置HTTP URL (API地址)
            curl_setopt($curl, CURLOPT_URL, $url);

            // 2. 设置HTTP HEADER (表单POST)
            $head = array(
                'Content-Type: application/x-www-form-urlencoded'
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head);

            // 3. 设置HTTP BODY (URL键值对)
            $body = http_build_query($params);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

            // 4. 调用API，获取响应结果
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_NOBODY, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            if ($response === false)
            {
                $response = false;
                break;
            }

            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($code != 200)
            {
                $response = false;
                break;
            }
        } while (0);

        curl_close($curl);
        return $response;
    }

    function getReqSign($params /* 关联数组 */, $appkey /* 字符串*/)
    {
        // 1. 字典升序排序
        ksort($params);

        // 2. 拼按URL键值对
        $str = '';
        foreach ($params as $key => $value)
        {
            if ($value !== '')
            {
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }

        // 3. 拼接app_key
        $str .= 'app_key=' . $appkey;

        // 4. MD5运算+转换大写，得到请求签名
        $sign = strtoupper(md5($str));
        return $sign;
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
    
    public function actionDb() {
        $query = new Query();
        $query->select("i.*,ip.*");
        $query->from("item i");
        $query->where(['i.id' => 1]);
        
        $query->leftJoin("item_price ip", 'ip.item_id = i.id');
//        dd($query->createCommand()->getSql());
        
        dump($query->one());
    }

}