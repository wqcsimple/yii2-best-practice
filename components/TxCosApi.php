<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 3/28/17
 * Time: 6:09 PM
 */
namespace app\components;

use qcloudcos\Cosapi;

class TxCosApi {
    
    const COS_APP_ID = "";
    const COS_SECRET_ID = "";
    const COS_SECRET_KEY = "";

    const BUCKET_DISC_01 = 'disk01';

    const APP_BUCKET_MAPPING = [
        'disk01' => self::BUCKET_DISC_01
    ];

    /**
     * @return \SCS
     */
    public static function prepare()
    {
        require_once '../lib/cos-php-sdk/include.php';

        // 设置COS所在的区域，对应关系如下：
        //     华南  -> gz
        //     华东  -> sh
        //     华北  -> tj
        Cosapi::setRegion('sh');
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
}