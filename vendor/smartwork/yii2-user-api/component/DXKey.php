<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 3/29/16
 * Time: 12:23
 */

namespace smartwork\user\api\component;


class DXKey extends \dix\base\component\DXKey
{
    public static function getKeyOfOrgNameByOrgId($org_id)
    {
        $org_id = intval($org_id);
        return "org.$org_id.name";
    }

    public static function getKeyOfApiStatUserActionRank($date = null)
    {
        if ($date === null)
        {
            $time = defined('RUN_START_TIME_INT') ? RUN_START_TIME_INT : time();
            $date = date('Y-m-d', $time);
        }

        return "smartwork.api.stat.user-action-rank.$date";
    }

}