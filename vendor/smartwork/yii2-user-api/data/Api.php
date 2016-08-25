<?php

namespace smartwork\user\api\data;

class Api
{
    const TYPE_USER   = '用户';

    public static function PathGuestCanAccess()
    {
        return [
            'user/login',
            'user/login-as-admin',
            'user/register',
            'user/password-reset',
            'user/login-by-wx',
            'user/login-as-admin-by-wx',
            'user/register-by-wx',
            'user/detail-by-phone',
            'common/phone-verification-code-send',
            'common/phone-verification-code-check',
        ];
    }

    public static function ActionList()
    {

        $user_actions = [

            [
                'type' => self::TYPE_USER,
                'name' => '登录',
                'action' => 'user/login',
                'token' => false,
                'params' => ['phone | i', 'password | s'],
                'response' => '\smartwork\user\api\service\UserService::login($phone, $password, $this->admin, $this->version)',
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '微信登录',
                'action' => 'user/login-by-wx',
                'token' => false,
                'params' => ['unionid | s'],
                'response' => [
                    'user' => '\smartwork\user\api\service\UserService::loginByWx($unionid, $this->admin, $this->version)',
                    'token' => '\smartwork\user\api\service\UserService::makeToken($this->admin, $this->version, $data["user"]["id"])'
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '桌面登录',
                'action' => 'user/login-as-admin',
                'token' => false,
                'params' => ['phone | i', 'password | s'],
                'response' => '\smartwork\user\api\service\UserService::loginAsAdmin($phone, $password, $this->admin, $this->version)',
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '桌面登录-微信',
                'action' => 'user/login-as-admin-by-wx',
                'token' => false,
                'params' => ['unionid | s'],
                'response' => '\smartwork\user\api\service\UserService::loginAsAdminByWx($unionid, $this->admin, $this->version)'
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '注册',
                'action' => 'user/register',
                'token' => false,
                'params' => ['phone | i', 'password | s', 'code | i'],
                'response' => [
                    'user' => '\smartwork\user\api\service\UserService::register($phone, $password, $code)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '微信注册',
                'action' => 'user/register-by-wx',
                'token' => false,
                'params' => ['unionid | s'],
                'response' => [
                    'user' => '\smartwork\user\api\service\UserService::registerByWx($unionid)',
                    'token' => '\smartwork\user\api\service\UserService::makeToken($this->admin, $this->version, $data["user"]["id"])'
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '登出',
                'action' => 'user/logout',
                'token' => true,
                'params' => [],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::logout($this->user_id)'
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '获取用户详细信息(通过电话)',
                'action' => 'user/detail-by-phone',
                'token' => false,
                'params' => ['phone | i'],
                'response' => [
                    'user' => '\smartwork\user\api\service\UserService::getUserDetailByPhone($phone)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '获取用户详细信息',
                'action' => 'user/detail',
                'token' => true,
                'params' => [],
                'response' => [
                    'user' => '\smartwork\user\api\service\UserService::getUserDetail($this->user_id)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '修改密码',
                'action' => 'user/password-update',
                'token' => true,
                'params' => ['old_password | s', 'password | s'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::updateUserPassword($this->user_id, $old_password, $password)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '重置密码',
                'action' => 'user/password-reset',
                'token' => false,
                'params' => ['phone | i', 'password | s', 'code | i'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::resetPassword($phone, $password, $code)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '更新头像',
                'action' => 'user/avatar-update',
                'token' => true,
                'params' => ['img | s'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::updateUserAvatar($this->user_id, $img) ',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '基本信息更新',
                'action' => 'user/basic-info-update',
                'token' => true,
                'params' => ['name | s, null', 'email | s, null', 'gender | i, -1', 'birthday | i, 0'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::updateUserBasicInfo($this->user_id, $name, $email, $gender, $birthday)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '绑定手机号更新，注意一定要验证手机号',
                'action' => 'user/phone-update',
                'token' => true,
                'params' => ['phone | i', 'code | i'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::updateUserPhone($this->user_id, $phone, $code)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '第三方账户信息更新',
                'action' => 'user/outer-user-info-update',
                'token' => true,
                'params' => ['type | i', 'outer_user_data | s'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::updateOuterUserInfo($this->user_id, $type, $outer_user_data)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '第三方账户列表',
                'action' => 'user/outer-user-list',
                'token' => true,
                'params' => [],
                'response' => [
                    'outer_user_list' => '\smartwork\user\api\service\UserService::getOuterUserList($this->user_id)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '第三方账户绑定',
                'action' => 'user/outer-user-bind',
                'token' => true,
                'params' => ['type | i', 'outer_user_id | s', 'outer_user_data | s'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::bindOuterUser($this->user_id, $type, $outer_user_id, $outer_user_data)',
                ]
            ],

            [
                'type' => self::TYPE_USER,
                'name' => '第三方账户解绑',
                'action' => 'user/outer-user-unbind',
                'token' => true,
                'params' => ['type | i'],
                'response' => [
                    'null' => '\smartwork\user\api\service\UserService::unbindOuterUser($this->user_id, $type)',
                ]
            ],

        ];

        return array_merge($user_actions);
    }


}