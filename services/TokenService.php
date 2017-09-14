<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/3/17
 * Time: 2:25 PM
 */
namespace app\services;


use app\models\Token;
use dix\base\exception\ServiceErrorSaveException;

class TokenService
{
    public static function makeToken($user_id, $type = Token::TYPE_ADMIN)
    {
        self::makeTokenInvalid($user_id, $type);

        $token = new Token();
        $token->token = self::generateToken();
        $token->type = $type;
        $token->status = Token::STATUS_VALID;
        $token->user_id = intval($user_id);
        $token->create_time = time();
        $token->update_time = $token->create_time;
        $token->expire_time = 0;

        if (!$token->save())
        {
            throw new ServiceErrorSaveException();
        }

        return $token->token;
    }

    public static function makeTokenInvalid($user_id, $type)
    {
        // Token::updateAll(['status' => Token::STATUS_INVALID], ['user_id' => $user_id, 'type' => $type]);
    }

    public static function generateToken()
    {
        mt_srand((double)microtime() * 10000);
        $key = md5(md5(uniqid(rand(), true)) . time());

        return $key;
    }
}