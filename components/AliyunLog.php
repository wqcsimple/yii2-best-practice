<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2018/6/26
 * Time: 12:17
 */

namespace app\components;

use Aliyun_Log_Client;

class AliyunLog
{

    private $endpoint = "cn-hangzhou.log.aliyuncs.com";
    private $access_key_id = "GFsvW1dOVH6UE6W4";
    private $access_key_secret = "Iq2IWCsj3sSZDqwncuzhaq7RXQqrdD";
    private $project_name = "dc-api";
    private $log_store = "base_log";

    private $client;

    public function __construct()
    {
        require_once '../lib/aliyun-log-php/Log_Autoload.php';

        $this->client = new Aliyun_Log_Client($this->endpoint, $this->access_key_id, $this->access_key_secret);
    }

    public function putLog($title, $contents)
    {
        if (!is_array($contents)) {
            $contents = [$contents];
        }

        $log_item = $this->initLogItem($contents);
        $request = new \Aliyun_Log_Models_PutLogsRequest($this->project_name, $this->log_store, $title, null, [$log_item]);

        try {
            return $this->client->putLogs($request);
        } catch (\Aliyun_Log_Exception $e) {
            \Yii::error($e->getErrorMessage());
        }
        return null;
    }

    public function getLogStores()
    {
        try {
            $request = new \Aliyun_Log_Models_ListLogstoresRequest($this->project_name);
            return $this->client->listLogstores($request)->getLogstores();
        } catch (\Aliyun_Log_Exception $e) {
            \Yii::error($e->getErrorMessage());
        }

        return null;
    }

    public function initLogItem($content)
    {
        $log_item = new \Aliyun_Log_Models_LogItem();
        $log_item->setTime(time());
        $log_item->setContents($content);

        return $log_item;
    }
}