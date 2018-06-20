<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2018/6/20
 * Time: 17:06
 */
namespace app\modules\client\v100\services;


class SyncService {

    public static function roleSync($data)
    {
        dump(json_decode($data, true));
        die;
    }
}