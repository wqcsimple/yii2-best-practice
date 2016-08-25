<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/26/16
 * Time: 1:07 PM
 */
namespace app\modules\admin\v100\services;

use app\components\DXConst;
use app\components\DXUtil;
use app\models\Admin;
use app\models\Token;
use dix\base\component\PasswordHash;
use dix\base\exception\ServiceErrorExistsException;
use dix\base\exception\ServiceErrorInvalidException;
use dix\base\exception\ServiceErrorLoginFailException;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorSaveException;

class AdminService 
{
    public static function login($_user_id, $username, $password)
    {
        $admin = Admin::findByUsername($username);
        if (!$admin)
        {
            throw new ServiceErrorNotExistsException();
        }

        if (!$admin->validatePassword($password))
        {
            throw new ServiceErrorLoginFailException('wrong password');
        }

        $token = AdminService::makeToken($admin->id);
        $admin = Admin::processRawDetail($admin);
        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
    
    public static function logout($_user_id)
    {
        self::makeTokenInvalid($_user_id, Token::TYPE_ADMIN);
    }

    public static function updatePassword($admin_id, $old_password, $password)
    {
        $admin = Admin::findById($admin_id);
        if (!$admin)
        {
            throw new ServiceErrorNotExistsException();
        }
        if (!$admin->validatePassword($old_password))
        {
            throw new ServiceErrorInvalidException("wrong old password");
        }

        $admin->password = Admin::encodePassword($password);
        if (!$admin->save())
        {
            throw new ServiceErrorSaveException();
        }

        self::makeTokenInvalid($admin_id, Token::TYPE_ADMIN);
    }
    
    public static function addAdmin($_user_id, $username, $name, $password, $email, $gender)
    {
        $admin = Admin::findByUsername($username);
        if ($admin)
        {
            throw new ServiceErrorExistsException();
        }
        
        $db_admin = new Admin();
        $db_admin->weight = DXConst::WEIGHT_NORMAL;
        $db_admin->username = $username;
        $db_admin->password = Admin::encodePassword($password);
        $db_admin->name = $name;
        $db_admin->email = $email;
        $db_admin->gender = $gender;
        
        if (!$db_admin->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_admin->errors]);
        }

        return Admin::processRaw($admin);
    }
    
    public static function getAdminList($_user_id, $name)
    {
        $query = Admin::find()->where(' weight >= 0 ');
        if ($name)
        {
            $query = $query->andWhere(['like', 'name', $name]);
        }
     
        $db_admin_list = $query->orderBy(['create_time' => SORT_DESC])->asArray()->all();
        $admin_list = DXUtil::formatModelList($db_admin_list, Admin::className());
        
        return [
            'list' => $admin_list
        ];
    }

    public static function getAdminInfo($_user_id)
    {
        return Admin::getRawById($_user_id);
    }
    
    public static function getAdminDetail($_user_id, $admin_id)
    {
        return Admin::getRawById($admin_id);
    }
    
    public static function updateAdminInfo($_user_id, $admin_id, $name, $email, $gender)
    {
        $db_admin = Admin::findById($admin_id);
        if (!$db_admin)
        {
            throw new ServiceErrorNotExistsException();
        }
        $db_admin->name = $name;
        $db_admin->email = $email;
        $db_admin->gender = $gender;
        if (!$db_admin->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_admin->errors]);
        }
    }
    
    public static function updateAdminPassword($_user_id, $admin_id, $password)
    {
        $db_admin = Admin::findById($admin_id);
        if (!$db_admin)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $db_admin->password = PasswordHash::create_hash($password);
        if (!$db_admin->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_admin->errors]);
        }
    }
    
    public static function deleteAdmin($_user_id, $admin_id)
    {
        $db_admin = Admin::findById($admin_id);
        if (!$db_admin)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $db_admin->weight = DXConst::WEIGHT_DELETED;
        if (!$db_admin->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_admin->errors]);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    public static function makeToken($admin_id, $type = Token::TYPE_ADMIN)
    {
        self::makeTokenInvalid($admin_id, $type);
      
        $token = new Token();
        $token->token = self::generateToken();
        $token->type = $type;
        $token->status = Token::STATUS_VALID;
        $token->user_id = intval($admin_id);
        $token->create_time = time();
        $token->update_time = $token->create_time;
        $token->expire_time = 0;
        
        if (!$token->save())
        {
            throw new ServiceErrorSaveException();
        }

        return $token->token;
    }

    public static function makeTokenInvalid($admin_id, $type)
    {
        // Token::updateAll(['status' => Token::STATUS_INVALID], ['user_id' => $admin_id, 'type' => $type]);
    }

    public static function generateToken()
    {
        mt_srand((double)microtime() * 10000);
        $key = md5(md5(uniqid(rand(), true)) . time());

        return $key;
    }
}