<?php

namespace app\models;

use dix\base\component\DXUtil;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $uid
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $name
 * @property integer $gender
 * @property integer $birthday
 * @property string $avatar
 * @property integer $source_id
 * @property integer $source_type
 * @property integer $source_client
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class User extends \smartwork\user\api\model\User
{
    public static function findByName($name)
    {
        $user_id_list = [];

        $db_user_list = self::find()->where(['like','name', $name])->asArray()->all();

        foreach ($db_user_list as $db_user)
        {
            $user = User::processRaw($db_user);
            if ($user)
            {
                $user_id_list[] = $user['id'];
            }
        }

        return self::getUserIdStrByUserList($user_id_list);
    }

    public static function getUserIdStrByUserList($user_id_list)
    {
        $user_id_list_checked = [];

        if($user_id_list)
        {
            foreach ($user_id_list as $user_id)
            {
                if ($user_id && User::exists($user_id))
                {
                    $user_id_list_checked[] = $user_id;
                }
            }
        }

        return $user_id_list_checked;
    }

    public static function getUserListByPage($limit, $offset)
    {
        $db_user_list = self::find()->where('weight >= 0')->limit($limit)->offset($offset)->asArray()->all();
        return DXUtil::formatModelList($db_user_list, User::className());
    }

    public static function getName($user)
    {
        $name = $user['name'];
        $username = $user['username'];
        $phone = $user['phone'];
        if ($name)
        {
            return $name;
        }

        return '未命名';
    }
    
    public static function getNameByUserId($user_id)
    {
        $user = self::getUserRawById($user_id);
        if ($user)
        {
            return self::getName($user);
        }
        return "未命名";
    }

    public static function processUserIds($userIds)
    {
        $user_id_array = array_filter(explode(',', $userIds));
        return User::getUserRawListByIdList($user_id_array);
    }
}
