<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/29/17
 * Time: 4:07 PM
 */
namespace app\modules\client\v100\services;


use app\models\Admin;
use app\models\Token;
use app\services\TokenService;
use dix\base\component\PasswordHash;
use dix\base\exception\ServiceErrorLoginFailException;
use dix\base\exception\ServiceErrorNotExistsException;

class AdminService {

    public static function login($username, $password)
    {
        $admin = Admin::findByUsername($username);
        if (!$admin) 
        {
            throw new ServiceErrorNotExistsException("用户不存在"); 
        }
        
        if (!$admin->validatePassword($password))
        {
            throw new ServiceErrorLoginFailException('密码错误');
        }
        
        $token = TokenService::makeToken($admin->id, Token::TYPE_ADMIN);
        $admin = Admin::processRawDetail($admin);
        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
}