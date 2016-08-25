<?php
/**
 * Created by PhpStorm.
 * User: yangcheng
 * Date: 16/2/3
 * Time: 13:48
 */

namespace smartwork\user\api\component;

use dix\base\component\DXUtil;
use dix\base\exception\ServiceErrorCreateInvitationException;
use dix\base\exception\ServiceErrorExistsException;
use dix\base\exception\ServiceErrorInvalidException;
use dix\base\exception\ServiceErrorInvalidInvitationException;
use dix\base\exception\ServiceErrorLoginFailException;
use dix\base\exception\ServiceErrorNotAllowedException;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorParamNotSetException;
use dix\base\exception\ServiceErrorPhoneHasBeenTakenException;
use dix\base\exception\ServiceErrorWrongParamException;
use dix\base\exception\ServiceException;
use GuzzleHttp\Client;

class SmartworkUserApi
{
    const APP_ID     = 'oatD7kRfchrBqIvu';
    const APP_SECRET = 'rtk9YN3uwupaqhIOrbszwjOIAgk1YPwq';
    const HOST       = 'sw-user-api.yuntick.com';
    //        const HOST = 'localhost:8080';

    const ACTION_USER_LOGIN                              = 'user/login';
    const ACTION_USER_LOGIN_AS_ADMIN                     = 'user/login-as-admin';
    const ACTION_USER_REGISTER                           = 'user/register';
    const ACTION_USER_DETAIL                             = 'user/detail';
    const ACTION_USER_DETAIL_BY_PHONE                    = 'user/detail-by-phone';
    const ACTION_USER_PASSWORD_UPDATE                    = 'user/password-update';
    const ACTION_USER_PASSWORD_RESET                     = 'user/password-reset';
    const ACTION_USER_PHONE_UPDATE                       = 'user/phone-update';
    const ACTION_USER_AVATAR_UPDATE                      = 'user/avatar-update';
    const ACTION_USER_BASIC_INFO_UPDATE                  = 'user/basic-info-update';
    const ACTION_USER_LOGIN_BY_OUTER_USER                = 'user/login-by-outer-user';
    const ACTION_USER_LOGIN_AS_ADMIN_BY_OUTER_USER       = 'user/login-as-admin-by-outer-user';
    const ACTION_USER_REGISTER_BY_OUTER_USER             = 'user/register-by-outer-user';
    const ACTION_USER_OUTER_USER_INFO_UPDATE             = 'user/outer-user-info-update';
    const ACTION_USER_OUTER_USER_INFO_UPDATE_BY_OUTER_ID = 'user/outer-user-info-update-by-outer-user-id';
    const ACTION_USER_OUTER_USER_LIST                    = 'user/outer-user-list';
    const ACTION_USER_OUTER_USER_BIND                    = 'user/outer-user-bind';
    const ACTION_USER_OUTER_USER_UNBIND                  = 'user/outer-user-unbind';

    const ACTION_TOKEN_DETAIL = 'token/detail';

    const ACTION_ORG_ORG_ROOT_CREATE              = 'org/org-root-create';
    const ACTION_ORG_CHILDREN                     = 'org/children';
    const ACTION_ORG_CHILDREN_TREE                = 'org/children-tree';
    const ACTION_ORG_CHILD_ADD                    = 'org/child-add';
    const ACTION_ORG_DELETE                       = 'org/delete';
    const ACTION_ORG_DETAIL                       = 'org/detail';
    const ACTION_ORG_INFO_UPDATE                  = 'org/info-update';
    const ACTION_ORG_ORG_ROOT_LIST                = 'org/org-root-list';
    const ACTION_ORG_ORG_ROOT_LIST_CREATE_BY_USER = 'org/org-root-list-create-by-user';
    const ACTION_ORG_ORG_LIST                     = 'org/org-list';

    const ACTION_ORG_MEMBER_ADD                     = 'org/member-add';
    const ACTION_ORG_MEMBER_SET_ROLE_AS_MANAGER     = 'org/member-set-role-as-manager';
    const ACTION_ORG_MEMBER_SET_ROLE_AS_NORMAL_USER = 'org/member-set-role-as-normal-user';
    const ACTION_ORG_SET_ORG_AS_DEFAULT             = 'org/set-org-as-default';
    const ACTION_ORG_ORG_DEFAULT                    = 'org/org-default';
    const ACTION_ORG_MEMBER_MOVE                    = 'org/member-move';
    const ACTION_ORG_MEMBER_DELETE                  = 'org/member-delete';
    const ACTION_ORG_USER_DELETE                    = 'org/user-delete';
    const ACTION_ORG_MEMBER_LIST                    = 'org/member-list';
    const ACTION_ORG_MEMBER_LIST_WITH_BASE_AUTH     = 'org/member-list-with-base-auth';
    const ACTION_ORG_MEMBER_UNASSIGNED_LIST         = 'org/member-unassigned-list';
    const ACTION_ORG_SUPERIOR_ID_LIST               = 'org/user-superior-id-list';

    const ACTION_ORG_INVITATION_CREATE                = 'org/invitation-create';
    const ACTION_ORG_INVITATION_DETAIL                = 'org/invitation-detail';
    const ACTION_ORG_INVITATION_UPDATE_STATUS_SUCCESS = 'org/invitation-update-status-success';

    const ACTION_AUTH_ORG_UPDATE  = 'auth/org-update';
    const ACTION_AUTH_USER_LIST   = 'auth/user-list';
    const ACTION_AUTH_USER_ADD    = 'auth/user-add';
    const ACTION_AUTH_USER_REMOVE = 'auth/user-remove';
    const ACTION_AUTH_LIST        = 'auth/list';
    const ACTION_AUTH_OA_CHECK    = 'auth/auth-oa-check';
    const ACTION_AUTH_AUTH        = 'auth/auth';

    public static function get($action, $data)
    {
        $url = 'http://' . self::HOST . '/private/1/' . $action;
        $client = new Client();
        $data['app_id'] = self::APP_ID;
        $data['app_secret'] = self::APP_SECRET;
        $res = $client->post($url, [
            'form_params' => $data
        ]);

        $response = @json_decode('' . $res->getBody(), true);

        return self::validateResponse($response);
    }

    public static function validateResponse($response)
    {
        if (!isset($response['code']))
        {
            throw new ServiceErrorInvalidException('api error');
        }
        $code = intval($response['code']);
        if ($code == 0)
        {
            return $response;
        }
        $message = '';
        if (isset($response['message']))
        {
            $message = $response['message'];
        }
        switch ($code)
        {
            case ServiceException::ERROR_INVALID:
                throw new ServiceErrorInvalidException("api error, " . $message);
                break;
            case ServiceException::ERROR_PARAM_NOT_SET:
                throw new ServiceErrorParamNotSetException($message, false);
                break;
            case ServiceException::ERROR_TOKEN_INVALID:
                throw new ServiceErrorInvalidException('uapi token invalid');
                break;
            case ServiceException::ERROR_LOGIN_FAIL:
                throw new ServiceErrorLoginFailException($message);//wrong password or not bind
                break;
            case ServiceException::ERROR_WRONG_PARAM:
                throw new ServiceErrorWrongParamException($message);
                break;
            case ServiceException::ERROR_NOT_EXIST:
                throw new ServiceErrorNotExistsException($message);
                break;
            case ServiceException::ERROR_EXIST:
                throw new ServiceErrorExistsException();
                break;
            case ServiceException::ERROR_ORG_NOT_EXIST:
                throw new ServiceErrorNotExistsException($message);
                break;
            case ServiceException::ERROR_ORG_MEMBER_NOT_EXISTS:
                throw new ServiceErrorNotExistsException($message);
                break;
            case ServiceException::ERROR_REGISTER:
                throw new ServiceErrorInvalidException($message);
                break;
            case ServiceException::ERROR_USER_NOT_EXISTS:
                throw new ServiceErrorNotExistsException($message);
                break;
            case ServiceException::ERROR_PHONE_HAS_BEEN_TAKEN:
                throw new ServiceErrorPhoneHasBeenTakenException();
                break;
            case ServiceException::ERROR_BIND_USER_BIND_EXISTS:
                throw new ServiceErrorInvalidException($message);
                break;
            case ServiceException::ERROR_CREATE_INVITATION:
                throw new ServiceErrorCreateInvitationException();
                break;
            case ServiceException::ERROR_INVALID_INVITATION:
                throw new ServiceErrorInvalidInvitationException();
                break;
            case ServiceException::ERROR_ORG_NO_DEFAULT:
                throw new ServiceErrorInvalidException($message);
                break;
            case ServiceException::ERROR_ACTION_NOT_ALLOWED:
                throw new ServiceErrorNotAllowedException($message);
                break;
            default:
                throw new ServiceErrorInvalidException("api error, code: " . $code . ' message: ' . $message);
                break;
        }
    }

    public static function validateResponseData($response, $params = [])
    {
        if (!isset($response['data']))
        {
            throw new ServiceErrorInvalidException("api error, didn't return data");
        }
        $data = $response['data'];
        foreach ($params as $param)
        {
            if (!isset($data[$param]))
            {
                throw new ServiceErrorInvalidException("api error, didn't return " . $param);
            }
        }
        return $data;
    }

    public static function userLogin($phone, $password)
    {
        return self::get(self::ACTION_USER_LOGIN, [
            'phone' => $phone,
            'password' => $password
        ]);
    }

    public static function userLoginAsAdmin($phone, $password)
    {
        return self::get(self::ACTION_USER_LOGIN_AS_ADMIN, [
            'phone' => $phone,
            'password' => $password
        ]);
    }

    public static function userRegister($phone, $password)
    {
        return self::get(self::ACTION_USER_REGISTER, [
            'phone' => $phone,
            'password' => $password
        ]);
    }

    public static function userDetail($uid)
    {
        return self::get(self::ACTION_USER_DETAIL, [
            'uid' => $uid
        ]);
    }

    public static function userDetailByPhone($phone)
    {
        return self::get(self::ACTION_USER_DETAIL_BY_PHONE, [
            'phone' => $phone
        ]);
    }

    public static function userPasswordUpdate($uid, $old_password, $password)
    {
        return self::get(self::ACTION_USER_PASSWORD_UPDATE, [
            'uid' => $uid,
            'old_password' => $old_password,
            'password' => $password
        ]);
    }

    public static function userPasswordReset($uid, $password)
    {
        return self::get(self::ACTION_USER_PASSWORD_RESET, [
            'uid' => $uid,
            'password' => $password
        ]);
    }

    public static function userPhoneUpdate($uid, $phone)
    {
        return self::get(self::ACTION_USER_PHONE_UPDATE, [
            'uid' => $uid,
            'phone' => $phone
        ]);
    }

    public static function userAvatarUpdate($uid, $avatar)
    {
        return self::get(self::ACTION_USER_AVATAR_UPDATE, [
            'uid' => $uid,
            'avatar' => $avatar
        ]);
    }

    public static function userBasicInfoUpdate($uid, $name, $email, $gender, $birthday)
    {
        return self::get(self::ACTION_USER_BASIC_INFO_UPDATE, [
            'uid' => $uid,
            'name' => $name,
            'email' => $email,
            'gender' => $gender,
            'birthday' => $birthday
        ]);
    }

    public static function userLoginByOuterUser($type, $outer_user_id)
    {
        return self::get(self::ACTION_USER_LOGIN_BY_OUTER_USER, [
            'type' => $type,
            'outer_user_id' => $outer_user_id
        ]);
    }

    public static function userLoginAsAdminByOuterUser($type, $outer_user_id)
    {
        return self::get(self::ACTION_USER_LOGIN_AS_ADMIN_BY_OUTER_USER, [
            'type' => $type,
            'outer_user_id' => $outer_user_id
        ]);
    }

    public static function userRegisterByOuterUser($type, $outer_user_id)
    {
        return self::get(self::ACTION_USER_REGISTER_BY_OUTER_USER, [
            'type' => $type,
            'outer_user_id' => $outer_user_id
        ]);
    }

    public static function userOuterUserInfoUpdate($type, $uid, $data)
    {
        return self::get(self::ACTION_USER_OUTER_USER_INFO_UPDATE, [
            'type' => $type,
            'uid' => $uid,
            'data' => $data
        ]);
    }

    public static function userOuterUserInfoUpdateByOuterUserId($type, $outer_user_id, $data)
    {
        return self::get(self::ACTION_USER_OUTER_USER_INFO_UPDATE_BY_OUTER_ID, [
            'type' => $type,
            'outer_user_id' => $outer_user_id,
            'data' => $data
        ]);
    }

    public static function userOuterUserList($uid = '', $user_id = '')
    {
        return self::get(self::ACTION_USER_OUTER_USER_LIST, [
            'uid' => $uid,
            'user_id' => $user_id,
        ]);
    }

    public static function userOuterUserBind($type, $uid, $outer_user_id, $data)
    {
        return self::get(self::ACTION_USER_OUTER_USER_BIND, [
            'type' => $type,
            'uid' => $uid,
            'outer_user_id' => $outer_user_id,
            'data' => $data
        ]);
    }

    public static function userOuterUserUnbind($type, $uid)
    {
        return self::get(self::ACTION_USER_OUTER_USER_UNBIND, [
            'type' => $type,
            'uid' => $uid
        ]);
    }

    public static function tokenDetail($token)
    {
        return self::get(self::ACTION_TOKEN_DETAIL, [
            'token' => $token,
        ]);
    }

    public static function orgOrgRootCreate($name, $type, $uid)
    {
        return self::get(self::ACTION_ORG_ORG_ROOT_CREATE, [
            'name' => $name,
            'type' => $type,
            'uid' => $uid
        ]);
    }

    public static function orgChildren($org_id, $level)
    {
        return self::get(self::ACTION_ORG_CHILDREN, [
            'org_id' => $org_id,
            'level' => $level
        ]);
    }

    public static function orgChildrenTree($org_id, $level)
    {
        return self::get(self::ACTION_ORG_CHILDREN_TREE, [
            'org_id' => $org_id,
            'level' => $level
        ]);
    }

    public static function orgOrgRootList($uid)
    {
        return self::get(self::ACTION_ORG_ORG_ROOT_LIST, [
            'uid' => $uid
        ]);
    }

    public static function orgOrgRootListCreateByUser($uid)
    {
        return self::get(self::ACTION_ORG_ORG_ROOT_LIST_CREATE_BY_USER, [
            'uid' => $uid
        ]);
    }

    public static function orgOrgList($uid, $org_root_id)
    {
        return self::get(self::ACTION_ORG_ORG_LIST, [
            'uid' => $uid,
            'org_root_id' => $org_root_id
        ]);
    }

    public static function orgChildAdd($name, $parent_id, $uid)
    {
        return self::get(self::ACTION_ORG_CHILD_ADD, [
            'name' => $name,
            'parent_id' => $parent_id,
            'uid' => $uid
        ]);
    }

    public static function orgDelete($org_id, $uid)
    {
        return self::get(self::ACTION_ORG_DELETE, [
            'org_id' => $org_id,
            'uid' => $uid
        ]);
    }

    public static function orgDetail($org_id)
    {
        return self::get(self::ACTION_ORG_DETAIL, [
            'org_id' => $org_id
        ]);
    }

    public static function orgInfoUpdate($org_id, $uid, $name, $desc, $person, $phone, $logo)
    {
        return self::get(self::ACTION_ORG_INFO_UPDATE, [
            'org_id' => $org_id,
            'uid' => $uid,
            'name' => $name,
            'desc' => $desc,
            'person' => $person,
            'phone' => $phone,
            'logo' => $logo
        ]);
    }

    public static function orgMemberAdd($org_id, $uid, $target_uid)
    {
        return self::get(self::ACTION_ORG_MEMBER_ADD, [
            'org_id' => $org_id,
            'uid' => $uid,
            'target_uid' => $target_uid
        ]);
    }

    public static function orgMemberSetRoleAsManager($uid, $org_id, $user_id)
    {
        return self::get(self::ACTION_ORG_MEMBER_SET_ROLE_AS_MANAGER, [
            'uid' => $uid,
            'org_id' => $org_id,
            'user_id' => $user_id,
        ]);
    }

    public static function orgMemberSetRoleAsNormalUser($uid, $org_id, $user_id)
    {
        return self::get(self::ACTION_ORG_MEMBER_SET_ROLE_AS_NORMAL_USER, [
            'uid' => $uid,
            'org_id' => $org_id,
            'user_id' => $user_id,
        ]);
    }

    public static function orgSetOrgAsDefault($uid, $org_root_id, $org_id)
    {
        return self::get(self::ACTION_ORG_SET_ORG_AS_DEFAULT, [
            'uid' => $uid,
            'org_root_id' => $org_root_id,
            'org_id' => $org_id
        ]);
    }

    public static function orgOrgDefault($uid, $org_root_id)
    {
        return self::get(self::ACTION_ORG_ORG_DEFAULT, [
            'uid' => $uid,
            'org_root_id' => $org_root_id
        ]);
    }

    public static function orgMemberMove($uid, $org_id, $target_org_id, $org_member_id)
    {
        return self::get(self::ACTION_ORG_MEMBER_MOVE, [
            'uid' => $uid,
            'org_id' => $org_id,
            'org_member_id' => $org_member_id,
            'target_org_id' => $target_org_id
        ]);
    }

    public static function orgMemberDelete($uid, $org_id, $org_member_id)
    {
        return self::get(self::ACTION_ORG_MEMBER_DELETE, [
            'uid' => $uid,
            'org_id' => $org_id,
            'org_member_id' => $org_member_id,
        ]);
    }

    public static function orgUserDelete($uid, $org_root_id, $target_uid)
    {
        return self::get(self::ACTION_ORG_USER_DELETE, [
            'org_root_id' => $org_root_id,
            'target_uid' => $target_uid,
            'uid' => $uid,
        ]);
    }

    public static function orgMemberList($org_id, $type = null)
    {
        $data = [
            'org_id' => $org_id,
            'type' => $type
        ];
        if ($type === null)
        {
            unset($data['type']);
        }

        return self::get(self::ACTION_ORG_MEMBER_LIST, $data);
    }

    public static function orgMemberListWithBaseAuth($org_root_id, $user_id_string = null)
    {
        $data = [
            'org_root_id' => $org_root_id,
            'user_id' => $user_id_string
        ];
        if ($user_id_string === null)
        {
            unset($data['user_id']);
        }

        return self::get(self::ACTION_ORG_MEMBER_LIST_WITH_BASE_AUTH, $data);
    }

    public static function orgMemberUnassignedList($org_root_id)
    {
        return self::get(self::ACTION_ORG_MEMBER_UNASSIGNED_LIST, [
            'org_root_id' => $org_root_id
        ]);
    }

    public static function orgSuperiorIdList($org_id)
    {
        return self::get(self::ACTION_ORG_SUPERIOR_ID_LIST, [
            'org_id' => $org_id,
        ]);
    }

    public static function orgInvitationCreate($uid, $org_root_id, $type, $valid_time)
    {
        return self::get(self::ACTION_ORG_INVITATION_CREATE, [
            'org_root_id' => $org_root_id,
            'uid' => $uid,
            'type' => $type,
            'valid_time' => $valid_time
        ]);
    }

    public static function orgInvitationDetail($token)
    {
        return self::get(self::ACTION_ORG_INVITATION_DETAIL, [
            'invitation_token' => $token
        ]);
    }

    public static function orgInvitationUpdateStatusSuccess($token, $uid)
    {
        return self::get(self::ACTION_ORG_INVITATION_UPDATE_STATUS_SUCCESS, [
            'invitation_token' => $token,
            'uid' => $uid
        ]);
    }

    public static function authOrgUpdate($uid, $org_root_id, $target_uid, $auth_name, $auth_value)
    {
        return self::get(self::ACTION_AUTH_ORG_UPDATE, [
            'uid' => $uid,
            'org_root_id' => $org_root_id,
            'target_uid' => $target_uid,
            'auth_name' => $auth_name,
            'auth_value' => $auth_value
        ]);
    }

    public static function authUserList($org_root_id)
    {
        return self::get(self::ACTION_AUTH_USER_LIST, [
            'org_root_id' => $org_root_id
        ]);
    }

    public static function authUserAdd($org_root_id, $uid, $target_uid)
    {
        return self::get(self::ACTION_AUTH_USER_ADD, [
            'org_root_id' => $org_root_id,
            'uid' => $uid,
            'target_uid' => $target_uid
        ]);
    }

    public static function authUserRemove($org_root_id, $uid, $target_uid)
    {
        return self::get(self::ACTION_AUTH_USER_REMOVE, [
            'org_root_id' => $org_root_id,
            'uid' => $uid,
            'target_uid' => $target_uid
        ]);
    }

    public static function authList($uid, $org_root_id)
    {
        return self::get(self::ACTION_AUTH_LIST, [
            'uid' => $uid,
            'org_root_id' => $org_root_id
        ]);
    }

    public static function authOACheck($uid, $org_root_id)
    {
        return self::get(self::ACTION_AUTH_OA_CHECK, [
            'uid' => $uid,
            'org_root_id' => $org_root_id
        ]);
    }

    public static function authAuth($user_id, $org_root_id)
    {
        return self::get(self::ACTION_AUTH_AUTH, [
            'user_id' => $user_id,
            'org_root_id' => $org_root_id
        ]);
    }


    // extra functions

    public static function getOrgName($org_id)
    {
        $redis = DXUtil::redis();
        $key = DXKey::getKeyOfOrgNameByOrgId($org_id);
        $org_name = $redis->get($key);
        if (empty($org_name))
        {
            $response = self::orgDetail($org_id);
            $data = self::validateResponseData($response, ['org_detail']);
            $org = $data['org_detail'];
            $org_name = $org['name'];
            //cache 24 hours
            $redis->setex($key, 3600 * 24, $org_name);
        }
        return $org_name;
    }


}