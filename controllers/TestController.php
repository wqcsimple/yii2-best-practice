<?php

namespace app\controllers;

use app\components\BaseApiController;
use app\components\Debug;
use app\components\DXUtil;
use app\components\WxNotify;
use app\models\Admin;
use app\models\User;
use dix\base\component\Redis;
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
        $data = [
            [
                "companyName" => "宁波青年优品珠宝首饰有限公司",
                "companyAddress" => "浙江省宁波市鄞州区潘火街道诚信路959号E幢3楼311室",
                "companyContact" => "0574-88306293",
                "bankName" => "平安银行宁波分行营业部",
                "bankAccount" => "15000097955512",
                "type" => "sale"
            ],
            [
                "companyName" => "宁波青年生活电子商务有限公司",
                "companyAddress" => "宁波市鄞州区嵩江中路518弄2号",
                "companyContact" => "0574-88197397",
                "bankName" => "中国工商银行宁波东门支行",
                "bankAccount" => "3901100009000080847",
                "type" => "process"
            ],
            [
                "companyName" => "青年优品融资租赁有限公司",
                "companyAddress" => "宁波市鄞州区下应北路567号九五九电商园E幢三楼",
                "companyContact" => "4001540028",
                "bankName" => "中国农业银行宁波市彩虹支行",
                "bankAccount" => "39420001040005472",
                "type" => "financing"
            ]
            
        ];
        
        $this->finish($data);
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