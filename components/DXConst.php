<?php
namespace app\components;

class DXConst extends \dix\base\component\DXConst
{
    const WEIGHT_NORMAL = 0;             //正常情况
    const WEIGHT_DELETED = -1;           //用户已删除该数据

    const ERROR_PARAM_NOT_SET = 1;
    const MEMBER_TYPE_NORMAL = 0;
    const MEMBER_TYPE_MANAGER = 1;
    const MEMBER_TYPE_WITH_AUTH_APV = 2;
    const MEMBER_TYPE_DISTINCT = 3;

    const NOTIFICATION_UNREAD = 0;
    const NOTIFICATION_READ = 1;
    
}


