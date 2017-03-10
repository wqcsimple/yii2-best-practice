<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2/17/17
 * Time: 10:38 AM
 * 安装sdk composer require qiniu/php-sdk
 */
namespace app\components;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class QiniuOssApi
{
    const ACCESS_KEY = "";
    const SECRET_KEY = "";

    const BUCKET_SIMPLE_LIFE = "simplelife";
    
    const APP_BUCKET_MAPPING = [
        'simplelife' => self::BUCKET_SIMPLE_LIFE
    ];

    /**
     * @return Auth  Qiniu\Auth
     */
    public static function prepare()
    {
        $auth = new Auth(self::ACCESS_KEY, self::SECRET_KEY);
        return $auth;
    }

    public static function getBucket($app)
    {
        $app_list = self::APP_BUCKET_MAPPING;
        if (in_array($app, array_keys($app_list)))
        {
            return $app_list[$app];
        }

        return null;
    }
    
    public static function getBucketManager()
    {
        $auth = self::prepare();
        $bucket_manager = new BucketManager($auth);
        return $bucket_manager;
    }

    /**
     * 获取文件信息
     * @param $bucket_name
     * @param $key
     * @return mixed
     */
    public static function getObjectInfo($bucket_name, $key)
    {
        $bucket_manager = self::getBucketManager();
        list($result, $err) = $bucket_manager->stat($bucket_name, $key);
        if ($err != null)
        {
            dump($err);
            die;
        } else {
            return $result;
        }
    }

    /**
     * @param $bucket_name
     * @param $prefix  // 要列取文件的公共前缀
     * @param $marker
     * @param $limit
     */
    public static function getObjectListByBucket($bucket_name, $prefix, $marker, $limit)
    {
        $bucket_manager = self::getBucketManager();

        list($file_list, $marker, $err) = $bucket_manager->listFiles($bucket_name, $prefix, $marker, $limit);
        if ($err != null)
        {
            dump($err);
            die;
        } else {
            return $file_list;
        }
    }
}
