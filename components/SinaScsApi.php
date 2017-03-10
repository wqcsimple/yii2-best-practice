<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 12/27/16
 * Time: 5:26 PM
 */
namespace app\components;

class SinaScsApi 
{
    const SINA_SCS_ACCESS_KEY = "";
    const SINA_SCS_SECRET_KEY = "";
    const SINA_SCS_USER_ID = "";

    const BUCKET_DISC_01 = 'disc01';

    const APP_BUCKET_MAPPING = [
        'disc01' => self::BUCKET_DISC_01
    ];

    /**
     * @return \SCS
     */
    public static function prepare()
    {
        if (!class_exists('SCS')) require_once '../lib/sina-scs/class/SCS.php';
        
        $scs = new \SCS(self::SINA_SCS_ACCESS_KEY, self::SINA_SCS_SECRET_KEY);
        
        return $scs;
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
    
    public static function getBucketList()
    {
        self::prepare();
        return \SCS::listBuckets(); 
    }
}