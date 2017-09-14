<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/29/17
 * Time: 4:07 PM
 */
namespace app\modules\client\v100\services;


use app\models\User;
use dix\base\exception\ServiceErrorNotExistsException;

class UserService {

    public static function login($username, $password)
    {
        $user = User::find()->where("weight >= 0")->andWhere(['username' => $username])->one();
        if (!$user) 
        {
            throw new ServiceErrorNotExistsException("用户不存在"); 
        }
        
        return null;
    }
}