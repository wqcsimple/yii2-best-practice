<?php

namespace smartwork\user\api\service;


use dix\base\component\PasswordHash;
use dix\base\component\PhoneVerification;
use dix\base\exception\ServiceErrorSaveException;
use dix\base\exception\ServiceErrorWrongVCodeException;
use dix\base\exception\ServiceErrorExistsException;
use dix\base\exception\ServiceErrorInvalidException;
use dix\base\exception\ServiceErrorNotExistsException;
use smartwork\user\api\component\SmartworkUserApi;
use smartwork\user\api\model\Token;
use smartwork\user\api\model\User;

class UserService
{
    public static function login($phone, $password, $client, $version)
    {
        $response = SmartworkUserApi::userLogin($phone, $password);
        $data = SmartworkUserApi::validateResponseData($response, ['user', 'token']);
        $user = $data['user'];
        $uid = $user['uid'];
        $id = $user['id'];
        $local_user = User::findByUid($uid);

        $token = self::saveTokenFromUserApi($data['token'], $client, $version);

        if (!$local_user)
        {
            self::regUserOnLocalUserNotExist($id, $uid, $phone, $password);
        }
        $local_user = self::updateUserInfoByRemoteUser($user);
        $local_user = User::processRawDetail($local_user);

        return [
            'user' => $local_user,
            'token' => $token->token
        ];
    }

    public static function loginAsAdmin($phone, $password, $client, $version)
    {
        $response = SmartworkUserApi::userLoginAsAdmin($phone, $password);
        $data = SmartworkUserApi::validateResponseData($response, ['user', 'token']);
        $user = $data['user'];
        $uid = $user['uid'];
        $id = $user['id'];
        $local_user = User::findByUid($uid);

        $token = self::saveTokenFromUserApi($data['token'], $client, $version);

        if (!$local_user)
        {
            self::regUserOnLocalUserNotExist($id, $uid, $phone, $password);
        }
        $local_user = self::updateUserInfoByRemoteUser($user);
        $local_user = User::processRawDetail($local_user);

        return [
            'user' => $local_user,
            'token' => $token->token
        ];
    }

    public static function loginAsAdminByWx($unionid, $client, $version)
    {
        $response = SmartworkUserApi::userLoginByOuterUser(1, $unionid);
        $data = SmartworkUserApi::validateResponseData($response, ['user', 'token']);

        $user = $data['user'];
        $uid = $user['uid'];
        $id = $user['id'];
        $local_user = User::findByUid($uid);
        $phone = isset($user['phone']) ? $user['phone'] : '';
        $phone = !empty($phone) ?: '';
        $password = isset($user['password']) ? $user['password'] : '';
        $password = !empty($password) ?: '';

        $token = self::saveTokenFromUserApi($data['token'], $client, $version);

        if (!$local_user)
        {
            self::regUserOnLocalUserNotExist($id, $uid, $phone, $password);
        }
        $local_user = self::updateUserInfoByRemoteUser($user);
        $local_user = User::processRawDetail($local_user);

        return [
            'user' => $local_user,
            'token' => $token->token
        ];
    }

    public static function regUserOnLocalUserNotExist($id, $uid, $phone, $password)
    {
        $user = new User();
        $user->id = $id;
        $user->uid = $uid;
        if ($phone)
        {
            $user->phone = '' . intval($phone);
        }
        if ($password)
        {
            $user->password = PasswordHash::create_hash($password);
        }
        if (!$user->save())
        {
            throw new ServiceErrorSaveException(['errors' => $user->errors]);
        }
        return $user;
    }

    public static function updateUserInfoByRemoteUser($user)
    {
        $local_user = User::findByUid($user['uid']);
        if (!$local_user)
        {
            return null;
        }
        if (isset($user['id']) && $user['id'])
        {
            $id = intval($user['id']);
            $local_user->id = $id;
        }

        if (isset($user['phone']) && $user['phone'])
        {
            $phone = '' . intval($user['phone']);
            $local_user->phone = $phone;
        }

        if (isset($user['avatar']) && $user['avatar'])
        {
            $local_user->avatar = $user['avatar'];
        }

        if (isset($user['name']) && $user['name'])
        {
            $local_user->name = $user['name'];
        }

        if (isset($user['email']) && $user['email'])
        {
            $local_user->email = $user['email'];
        }

        if (isset($user['username']) && $user['username'])
        {
            $local_user->username = $user['username'];
        }

        if (isset($user['gender']))
        {
            $gender = '' . intval($user['gender']);
            $local_user->gender = $gender;
        }

        if (isset($user['birthday']) && $user['birthday'])
        {
            $birthday = '' . intval($user['birthday']);
            $local_user->birthday = $birthday;
        }

        if (!$local_user->save())
        {
            throw new ServiceErrorSaveException(['errors' => $local_user->errors]);
        }
        return $local_user;
    }

    public static function updateUserInfoFromUapi($uid, $id = null)
    {
        $local_user = User::findByUid($uid) ?: (User::findById($id));
        if (!$local_user)
        {
            return null;
        }
        $response = SmartworkUserApi::userDetail($local_user->uid);
        $data = SmartworkUserApi::validateResponseData($response, ['user']);
        $user = $data['user'];
        if (isset($user['id']) && $user['id'])
        {
            $id = intval($user['id']);
            $local_user->id = $id;
        }

        if (isset($user['phone']) && $user['phone'])
        {
            $phone = '' . intval($user['phone']);
            $local_user->phone = $phone;
        }

        if (isset($user['avatar']) && $user['avatar'])
        {
            $local_user->avatar = $user['avatar'];
        }

        if (isset($user['name']) && $user['name'])
        {
            $local_user->name = $user['name'];
        }

        if (isset($user['email']) && $user['email'])
        {
            $local_user->email = $user['email'];
        }

        if (isset($user['username']) && $user['username'])
        {
            $local_user->username = $user['username'];
        }

        if (isset($user['gender']))
        {
            $gender = '' . intval($user['gender']);
            $local_user->gender = $gender;
        }

        if (isset($user['birthday']) && $user['birthday'])
        {
            $birthday = '' . intval($user['birthday']);
            $local_user->birthday = $birthday;
        }

        if (!$local_user->save())
        {
            throw new ServiceErrorSaveException(['errors' => $local_user->errors]);
        }
        return $local_user;
    }

    public static function tokenDetail($token)
    {
        $response = SmartworkUserApi::tokenDetail($token);
        if (isset($response['data']['token']))
        {
            return $response['data']['token'];
        }

        return null;
    }

    public static function makeToken($client, $version, $userId, $type = Token::TYPE_USER)
    {
        //        self::makeUserTokenInvalid($userId, $type);

        $token = new Token();
        $token->token = self::generateToken();
        $token->client = $client;
        $token->version = $version;
        $token->type = $type;
        $token->status = Token::STATUS_VALID;
        $token->user_id = intval($userId);
        $token->create_time = time();
        $token->update_time = $token->create_time;
        $token->expire_time = 0;

        if (!$token->save())
        {
            throw new ServiceErrorSaveException();
        }

        return $token->token;
    }

    public static function saveTokenFromUserApi($remote_token)
    {
        $token = new Token();
        $token->token = $remote_token['token'];
        $token->type = $remote_token['type'];
        $token->status = Token::STATUS_VALID;
        $token->user_id = intval($remote_token['user_id']);
        $token->create_time = intval($remote_token['create_time']);
        $token->update_time = intval($remote_token['update_time']);
        $token->expire_time = intval($remote_token['expire_time']);

        if (!$token->save())
        {
            throw new ServiceErrorSaveException();
        }

        $token->refresh();

        return $token;
    }

    public static function generateToken()
    {
        mt_srand((double)microtime() * 10000);
        $key = md5(md5(uniqid(rand(), true)) . time());

        return $key;
    }

    public static function makeUserTokenInvalid($userId, $type)
    {
        Token::updateAll(['status' => Token::STATUS_INVALID], ['user_id' => $userId, 'type' => $type]);
    }

    public static function loginByWx($unionid, $client, $version)
    {
        return self::loginByOuterUser(1, $unionid, $client, $version);
    }

    public static function loginByOuterUser($type, $outer_user_id, $client, $version)
    {
        $response = SmartworkUserApi::userLoginByOuterUser($type, $outer_user_id);
        $data = SmartworkUserApi::validateResponseData($response, ['user']);

        $user = $data['user'];
        $uid = $user['uid'];
        $id = $user['id'];
        $local_user = User::findByUid($uid);
        $phone = isset($user['phone']) ? $user['phone'] : '';
        $phone = !empty($phone) ?: '';
        $password = isset($user['password']) ? $user['password'] : '';
        $password = !empty($password) ?: '';

        $token = self::saveTokenFromUserApi($data['token'], $client, $version);

        if (!$local_user)
        {
            self::regUserOnLocalUserNotExist($id, $uid, $phone, $password);
        }
        $local_user = self::updateUserInfoByRemoteUser($user);
        $local_user = User::processRawDetail($local_user);

        return [
            'user' => $local_user,
            'token' => $token->token
        ];
    }

    public static function register($phone, $password, $code)
    {
        if (!PhoneVerification::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }
        $db_user = User::findByPhone($phone);
        if ($db_user)
        {
            throw new ServiceErrorExistsException();
        }
        $response = SmartworkUserApi::userRegister($phone, $password);
        $data = SmartworkUserApi::validateResponseData($response, ['user']);
        if (!isset($data['user']))
        {
            throw new ServiceErrorInvalidException("api error, didn't return user");
        }
        $user = $data['user'];
        $uid = $user['uid'];

        $db_user = self::regUserOnLocalUserNotExist($user['id'], $uid, $phone, $password);

        $db_user = User::processRawDetail($db_user);

        return $db_user;
    }

    public static function registerByWx($unionid)
    {
        return self::registerByOuterUser(1, $unionid);
    }

    public static function registerByOuterUser($type, $outer_user_id)
    {
        $response = SmartworkUserApi::userRegisterByOuterUser(intval($type), $outer_user_id);
        $data = SmartworkUserApi::validateResponseData($response, ['user']);
        if (!isset($data['user']))
        {
            throw new ServiceErrorInvalidException("api error, didn't return user");
        }
        $user = $data['user'];
        $uid = $user['uid'];
        $local_user = User::findByUid($uid);

        if (!$local_user)
        {
            $local_user = self::regUserOnLocalUserNotExist($user['id'], $uid, null, null);
        }
        else
        {
            $local_user = self::updateUserInfoFromUapi($uid);
        }
        $local_user = User::processRawDetail($local_user);

        return $local_user;
    }

    public static function logout($user_id)
    {
        self::makeUserTokenInvalid($user_id, Token::TYPE_USER);
    }

    public static function getUserDetailByPhone($phone)
    {
        $user = User::findByPhone($phone);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        $user = self::updateUserInfoFromUapi($user->uid);
        $user = User::processRawDetail($user);

        return $user;
    }

    public static function getUserDetail($user_id)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        $user = self::updateUserInfoFromUapi($user->uid);
        $user = User::processRawDetail($user);

        return $user;
    }

    public static function updateUserPassword($user_id, $old_password, $password)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        if ($old_password == $password)
        {
            throw new ServiceErrorInvalidException(" two password are same");
        }
        SmartworkUserApi::userPasswordUpdate($user->uid, $old_password, $password);

        $user->password = PasswordHash::create_hash($password);
        $user->save();

        self::makeUserTokenInvalid($user_id, Token::TYPE_USER);
    }

    public static function resetPassword($phone, $password, $code)
    {
        $user = User::findByPhone($phone);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        if (!PhoneVerification::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }

        SmartworkUserApi::userPasswordReset($user->uid, $password);
    }

    public static function updateUserAvatar($user_id, $img)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        SmartworkUserApi::userAvatarUpdate($user->uid, $img);
        self::updateUserInfoFromUapi($user->uid);
    }

    public static function updateUserBasicInfo($user_id, $name, $email, $gender, $birthday)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        SmartworkUserApi::userBasicInfoUpdate($user->uid, $name, $email, $gender, $birthday);
        self::updateUserInfoFromUapi($user->uid);
    }

    public static function updateUserPhone($user_id, $phone, $code)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        if (!PhoneVerification::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }
        SmartworkUserApi::userPhoneUpdate($user->uid, $phone);
        self::updateUserInfoFromUapi($user->uid);
    }

    public static function updateOuterUserInfo($user_id, $type, $outer_user_data)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        SmartworkUserApi::userOuterUserInfoUpdate($type, $user->uid, $outer_user_data);
    }

    public static function getOuterUserList($user_id)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        $response = SmartworkUserApi::userOuterUserList($user->uid);
        $data = SmartworkUserApi::validateResponseData($response, ['user_list']);
        return $data['user_list'];
    }

    public static function bindOuterUser($user_id, $type, $outer_user_id, $outer_user_data)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        SmartworkUserApi::userOuterUserBind($type, $user->uid, $outer_user_id, $outer_user_data);
    }

    public static function unbindOuterUser($user_id, $type)
    {
        $user = User::findById($user_id);
        if (!$user)
        {
            throw new ServiceErrorNotExistsException();
        }
        SmartworkUserApi::userOuterUserUnbind($type, $user->uid);
    }

}