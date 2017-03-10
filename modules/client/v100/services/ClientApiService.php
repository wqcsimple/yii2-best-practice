<?php

namespace app\modules\client\v100\services;

use app\components\DXKey;
use app\models\Token;
use app\modules\client\v100\data\Api;
use dix\base\component\Redis;
use dix\base\exception\ServiceErrorParamNotSetException;
use dix\base\exception\ServiceErrorSaveException;
use dix\base\exception\ServiceErrorTokenInvalidException;

class ClientApiService
{

    public static function before($params, $actionId, $controllerId)
    {
        $route = $controllerId . '/' . $actionId;
        if (in_array($route, Api::PathGuestCanAccess()))
        {
            if (isset($params['token']))
            {
                $token = $params['token'];
                if ($token)
                {
                    self::prepareData($token, false);
                }
            }
            return null;
        }
        if (!isset($params['token']))
        {
            throw new ServiceErrorParamNotSetException("token");
        }

        return self::prepareData($params['token'], true);
    }

    public static function prepareData($token, $strict)
    {
        $db_token = Token::findValidTokenByToken($token, Token::TYPE_USER);
        if (!$db_token)
        {
            if (!$strict)
            {
                return null;
            }
            throw new ServiceErrorTokenInvalidException();
        }

        $db_token->update_time = time();
        if (!$db_token->save())
        {
            throw new ServiceErrorSaveException(['errors' => $db_token->errors]);
        }

        return $db_token->user_id;
    }

    public static function checkParams($params, $requiredParams)
    {
        foreach ($requiredParams as $p)
        {
            if (!isset($params[$p]) || $params[$p] === '')
            {
                throw new ServiceErrorParamNotSetException($p);
            }
        }
    }

    public static function doUserStat($user_id)
    {
        $key = DXKey::getKeyOfApiStatUserActionRank();
        $redis = Redis::client();

        $redis->zincrby($key, 1, strval($user_id));
    }

    public static function getUserStat($date, $offset = 0, $limit = 100)
    {
        $key = DXKey::getKeyOfApiStatUserActionRank($date);
        $redis = Redis::client();
        $rank_list = $redis->zrevrangebyscore($key, '+inf', '-inf', ['WITHSCORES' => true, 'LIMIT' => ['OFFSET' => $offset, 'COUNT' => $limit]]);
        return $rank_list;
    }
}