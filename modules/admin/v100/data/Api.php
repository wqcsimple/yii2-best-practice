<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 5/26/15
 * Time: 23:41
 */
namespace app\modules\admin\v100\data;

class Api
{
    const TYPE_COMMON = '基本';
    const TYPE_USER   = '用户';
    const TYPE_ADMIN = "管理员";
    const TYPE_CONTACT = '联系';

    public static function PathGuestCanAccess()
    {
        return [
            'admin/login',
            'contact/save'
        ];
    }

    public static function ActionList()
    {
        
        $admin_actions = [
            [
                'type' => self::TYPE_ADMIN,
                'name' => '登录',
                'action' => 'admin/login',
                'token' => true,
                'params' => ['username | s', 'password | s'],
                'response' =>  '\app\modules\admin\v100\services\AdminService::login($this->user_id, $username, $password)'
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '登出',
                'action' => 'admin/logout',
                'token' => true,
                'params' => [],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\AdminService::logout($this->user_id)'
                ]
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '修改密码',
                'action' => 'admin/update-password',
                'token' => true,
                'params' => ['old_password | s', 'password | s'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\AdminService::updatePassword($this->user_id, $old_password, $password)',
                ]
            ],
            
            [
                'type' => self::TYPE_ADMIN,
                'name' => '添加管理员',
                'action' => 'admin/add',
                'token' => true,
                'params' => ['username | s', 'name | s', 'password | s', 'email | s, null', 'gender | i, null'],
                'response' => [
                    'admin' => '\app\modules\admin\v100\services\AdminService::addAdmin($this->user_id, $username, $name, $password, $email, $gender)',
                ]
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员列表',
                'action' => 'admin/list',
                'token' => true,
                'params' => ['name | s, null'],
                'response' => '\app\modules\admin\v100\services\AdminService::getAdminList($this->user_id, $name)',
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员自己的信息',
                'action' => 'admin/info',
                'token' => true,
                'params' => [],
                'response' => [
                    'info' =>'\app\modules\admin\v100\services\AdminService::getAdminInfo($this->user_id)',
                ]
            ],
            
            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员详情',
                'action' => 'admin/detail',
                'token' => true,
                'params' => ['admin_id | i, 0'],
                'response' => [
                    'detail' => '\app\modules\admin\v100\services\AdminService::getAdminDetail($this->user_id, $admin_id)', 
                ]
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员信息更新',
                'action' => 'admin/info-update',
                'token' => true,
                'params' => ['admin_id | i, 0', 'name | s, null', 'email | s, null', 'gender | i, null'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\AdminService::updateAdminInfo($this->user_id, $admin_id, $name, $email, $gender)'
                ]
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员更改密码',
                'action' => 'admin/password-update',
                'token' => true,
                'params' => ['admin_id | i, 0', 'password | s, null'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\AdminService::updateAdminPassword($this->user_id, $admin_id, $password)'
                ]
            ],

            [
                'type' => self::TYPE_ADMIN,
                'name' => '管理员移除',
                'action' => 'admin/delete',
                'token' => true,
                'params' => ['admin_id | i, 0'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\AdminService::deleteAdmin($this->user_id, $admin_id)'
                ]
            ],
        ];

        $common_actions = [

            [
                'type' => self::TYPE_COMMON,
                'name' => '发送手机验证码',
                'action' => 'common/phone-verification-code-send',
                'token' => false,
                'params' => ['phone | i'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\CommonService::sendPhoneVCode($phone)',
                ]
            ],

            [
                'type' => self::TYPE_COMMON,
                'name' => '检查手机验证码',
                'action' => 'common/phone-verification-code-check',
                'token' => false,
                'params' => ['phone | i', 'code | i'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\CommonService::checkPhoneVCode($phone, $code)',
                ]
            ],
        ];
        
        $contact_actions = [
            [
                'type' => self::TYPE_CONTACT,
                'name' => 'contact - 提交联系方式',
                'action' => 'contact/save',
                'token' => false,
                'params' => ['contact_id | i, 0', 'content | s, null', 'name | s, null', 'phone | s, null', 'email | s, null'],
                'response' => [
                    'contact_id' => '\app\modules\admin\v100\services\ContactService::saveContact($contact_id, $content, $name, $phone, $email)',
                ]
            ],

            [
                'type' => self::TYPE_CONTACT,
                'name' => 'contact - 联系方式列表',
                'action' => 'contact/list',
                'token' => false,
                'params' => ['page | i, 1', 'begin_date | s, null', 'end_date | s, null', 'name | s, null'],
                'response' => '\app\modules\admin\v100\services\ContactService::getContactList($this->user_id, $page, $begin_date, $end_date, $name)',
            ],

            [
                'type' => self::TYPE_CONTACT,
                'name' => 'contact - 删除',
                'action' => 'contact/remove',
                'token' => false,
                'params' => ['contact_id | i'],
                'response' => [
                    'null' => '\app\modules\admin\v100\services\ContactService::removeContact($this->user_id, $contact_id)',
                ]
            ],

            [
                'type' => self::TYPE_CONTACT,
                'name' => 'contact - 详情',
                'action' => 'contact/detail',
                'token' => false,
                'params' => ['contact_id | i'],
                'response' => [
                    'contact' => '\app\modules\admin\v100\services\ContactService::getContactDetail($this->user_id, $contact_id)',
                ]
            ],
        ];


        return array_merge($admin_actions, $common_actions, $contact_actions);
    }


}