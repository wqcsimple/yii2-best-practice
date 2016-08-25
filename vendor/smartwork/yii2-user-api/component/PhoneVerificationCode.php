<?php

namespace dix\base\component;

use dix\base\component\DXUtil;
use dix\base\component\Redis;
use dix\base\exception\ServiceErrorSendPhoneVCodeTooOften;
use smartwork\user\api\component\DXKey;

class PhoneVerificationCode extends \yii\base\Object
{
    public static function send($phone)
    {
        $code = rand(1000, 9999);

        $redis = Redis::client();
        $redis_key_phone_verification_code = DXKey::getKeyOfPhoneVerificationCode($phone);
        $redis_key_phone_verification_code_send_time = DXKey::getKeyOfPhoneVerificationCodeSendTime($phone);

        $rcode = $redis->get($redis_key_phone_verification_code);
        $send_time = $redis->get($redis_key_phone_verification_code_send_time);
        $now = time();

        if ($rcode && $send_time && intval($send_time) + 1800 > $now)
        {
            // $code = intval($rcode);
        }

        if ($now - $send_time < 60)
        {
            throw new ServiceErrorSendPhoneVCodeTooOften();
        }

        $redis->set($redis_key_phone_verification_code, $code);
        $redis->set($redis_key_phone_verification_code_send_time, $now);

        self::sendCode($phone, $code);
    }

    public static function sendCode($phone, $code)
    {
        require(__DIR__ . '/../lib/taobaosdk/TopSdk.php');

        $c = new \TopClient();
        $c->appkey = '23346710';
        $c->secretKey = 'a714e32d5b8fec6b272a90661cc29874';
        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setExtend("123456"); // 公共回传参数
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("云骐科技");
        $req->setSmsParam(DXUtil::jsonEncode([
            'code' => strval($code),
            'product' => 'SmartWork'
        ]));
        $req->setRecNum(strval($phone));
        $req->setSmsTemplateCode("SMS_7795653");
        $resp = $c->execute($req);
    }

    public static function validate($phone, $code)
    {
        $valid = false;
        $redis = Redis::client();
        $redis_key_phone_verification_code = DXKey::getKeyOfPhoneVerificationCode($phone);
        $redis_key_phone_verification_code_send_time = DXKey::getKeyOfPhoneVerificationCodeSendTime($phone);
        $vcode = $redis->get($redis_key_phone_verification_code);
        $send_time = $redis->get($redis_key_phone_verification_code_send_time);
        if ($vcode && intval($vcode) == $code && $send_time && intval($send_time) + 1800 > time())
        {
            $valid = true;
        }

        return $valid;
    }

}