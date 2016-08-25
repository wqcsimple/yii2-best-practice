<?php
namespace app\components;

class DXUtil extends \dix\base\component\DXUtil
{
    public static function ArrUnique($lists, $key)
    {
        $list = [];
        foreach ($lists as $v)
        {
            $list[] = $v[$key];
        }

        $list = array_unique($list);

        return $list;
    }

    /**
     * 获取id 和id字符串的拼接字符串
     * @param $id
     * @param $id_string
     * @param $is_sort
     * @return string
     */
    public static function mergeIdAndIdString($id, $id_string, $is_sort = true)
    {
        $id_string_arr = DXUtil::getIdArrayFromIdString($id_string);
        if (!in_array($id, $id_string_arr))
        {
            array_push($id_string_arr, $id);
        }

        if ($is_sort)
        {
            asort($id_string_arr);
        }

        $result_string = implode(',', $id_string_arr);

        return $result_string;
    }

    public static function removeIdFromIdString($remove_id, $id_string)
    {
        if (!is_string($id_string))
        {
            return null;
        }

        $id_string_arr = \dix\base\component\DXUtil::getIdArrayFromIdString($id_string);
        foreach ($id_string_arr as $key => $user_id)
        {
            if ($user_id == strval($remove_id))
            {
                unset($id_string_arr[$key]);
            }
        }

        return implode(",", $id_string_arr);
    }
}