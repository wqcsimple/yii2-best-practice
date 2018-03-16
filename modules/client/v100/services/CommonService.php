<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 3/10/17
 * Time: 1:46 PM
 */
namespace app\modules\client\v100\services;

use app\components\DXUtil;
use app\components\PhoneVerification;
use dix\base\component\Redis;
use dix\base\exception\ServiceErrorWrongVCodeException;

class CommonService
{

    public static function sendPhoneVCode($phone)
    {
        PhoneVerification::send($phone, function($phone, $code){
            PhoneVerification::sendCode($phone, $code);
        });
    }

    public static function checkPhoneVCode($phone, $code)
    {
        if (!PhoneVerification::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }
    }

    
    /**
     * usage
     *  //    $courier = CommonService::generateValidUid(function($uid){
    //            return Courier::uidExists($uid);
    //        }, function($uid) use($admin_id) {
    //            $courier = new Courier();
    //            $courier->uid = $uid;
    //            if ($courier->save())
    //            {
    //                return $courier;
    //            }
    //
    //            throw new ServiceErrorSaveException('保存失败', ['errors' => $courier->errors]);
    //        });
     * @param $uid_valid_check_func
     * @param $uid_valid_func
     * @return bool
     */
    public static function generateValidUid($uid_valid_check_func, $uid_valid_func)
    {
        $uid = null;
        $try_register_count = 0;

        // try to max of 3 times to register
        while (true)
        {
            $try_register_count++;
            if ($try_register_count > 3)
            {
                return false;
            }

            // generate an available uid
            $try_generate_uid_count = 0;
            $should_start_new_register_try = false;
            while (true)
            {
                $uid = DXUtil::generateRandomString(7);
                if (!$uid_valid_check_func($uid))
                {
                    break;
                }

                $try_generate_uid_count++;
                if ($try_generate_uid_count >= 3)
                {
                    $should_start_new_register_try = true;
                    break;
                }
            }

            if ($should_start_new_register_try)
            {
                continue;
            }

            // lock uid
            if (Redis::lock('company.uid', $uid))
            {
                return $uid_valid_func($uid);
            }
        }


        return false;
    }
}