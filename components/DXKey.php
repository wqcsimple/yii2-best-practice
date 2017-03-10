<?php
namespace app\components;

class DXKey extends \dix\base\component\DXKey
{
    public static function getKeyOfUser($user_id)
    {
        $user_id = intval($user_id);
        return "smartwork.user.id.$user_id";
    }

    public static function getKeyOfApvUserList($user_id)
    {
        $user_id = intval($user_id);
        return "apv.user.list.$user_id";
    }

    public static function getKeyOfOrgRootId($org_root_id)
    {
        $org_root_id = intval($org_root_id);
        return "org.root.id.$org_root_id";
    }

    public static function getKeyOfStatisticsApvCommonTool($user_id)
    {
        $user_id = intval($user_id);
        return "statistics.apv.common.tool.$user_id";
    }

    public static function getKeyOfConfigApvProcessByOrgRootId($org_root_id)
    {
        $org_root_id = intval($org_root_id);
        return "config.apv.process.$org_root_id";
    }

    public static function getKeyOfOrgNameByOrgId($org_id)
    {
        $org_id = intval($org_id);
        return "org.basic.info.name.$org_id";
    }

    public static function getKeyOfOrgDetail($org_id)
    {
        $org_id = intval($org_id);
        return "org.$org_id.detail";
    }

    public static function getKeyOfApiStatUserActionRank($date = null)
    {
        if ($date === null)
        {
            $time = defined('RUN_START_TIME_INT') ? RUN_START_TIME_INT : time();
            $date = date('Y-m-d', $time);
        }

        return "data.collection.api.stat.user-action-rank.$date";
    }

    public static function getKeyOfPayCountOfToday()
    {
        $date = date('Ymd', time());
        return "pay.count.today.$date";
    }
}