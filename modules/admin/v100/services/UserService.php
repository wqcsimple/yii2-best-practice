<?php

namespace app\modules\admin\v100\services;

use app\models\User;
use dix\base\component\DXUtil;

class UserService 
{
    public static function getRawById($id)
    {
        return User::processRaw(User::findById($id));
    }

    public static function getUserList($user_id)
    {
        $db_user_list = User::find()->where('weight >= 0')->asArray()->all();

        return DXUtil::formatModelList($db_user_list, User::className());
    }

    public static function getRawUserListFromIdString($id_string)
    {
        $id_list = DXUtil::getIdArrayFromIdString($id_string);

        $db_task_list = User::find()->where('weight >= 0')->andWhere(['in', 'id', $id_list])
            ->orderBy([
                'create_time' => SORT_DESC,
                'update_time' => SORT_DESC,
            ])->asArray()->all();

        return DXUtil::formatModelList($db_task_list, User::className());

    }

}