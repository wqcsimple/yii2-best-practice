<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/13/16
 * Time: 3:53 PM
 */
namespace app\services;

use app\components\DXUtil;
use app\modules\client\v100\services\ProjectMemberService;
use dix\base\component\Redis;

class WebSocketService
{
    const KEY_SMARTWORK_WEBSOCKET_MESSAGE = 'smartwork.websocket.message';
    
    const EVENT_NOTIFICATION = 1;
    const EVENT_TASK_DETAIL = 2;
    const EVENT_TASK_GROUP = 3;
    const EVENT_PROJECT = 4;
    const EVENT_TASK_COMMENT = 5;
    const EVENT_TASK_MEMBER = 6;
    
    const TARGET_TYPE_NOTIFICATION = 100; //发送通知
    const TARGET_TYPE_NOTIFICATION_SET_READ = 101; //将通知设为已读
    
    const TARGET_TYPE_TASK = 200;
    const TARGET_TYPE_TASK_CONTENT_EDIT = 201;
    const TARGET_TYPE_TASK_TITLE_EDIT = 202;
    
    const TARGET_TYPE_TASK_GROUP = 300;
    const TARGET_TYPE_TASK_GROUP_ADD = 301;
    const TARGET_TYPE_TASK_GROUP_REMOVE = 302;
    const TARGET_TYPE_TASK_GROUP_TASK_ADD = 303;
    const TARGET_TYPE_TASK_GROUP_TASK_REMOVE = 304;
    const TARGET_TYPE_TASK_GROUP_EDIT = 305;
    const TARGET_TYPE_TASK_GROUP_SORT_SAVE = 306;
    const TARGET_TYPE_TASK_SORT_SAVE = 307;

    const TARGET_TYPE_PROJECT = 400;
    const TARGET_TYPE_PROJECT_EDIT = 401;
    const TARGET_TYPE_PROJECT_ADD = 402;
    const TARGET_TYPE_PROJECT_REMOVE = 403;
    const TARGET_TYPE_PROJECT_MEMBER_ADD = 404;
    const TARGET_TYPE_PROJECT_MEMBER_REMOVE = 405;
    
    const TARGET_TYPE_TASK_COMMENT = 500;
    const TARGET_TYPE_TASK_COMMENT_ADD = 501;
    const TARGET_TYPE_TASK_COMMENT_REMOVE = 502;
    
    const TARGET_TYPE_TASK_MEMBER = 600;
    const TARGET_TYPE_TASK_MEMBER_ADD = 601; //任务添加成员
    const TARGET_TYPE_TASK_MEMBER_REMOVE = 602; //任务移除成员
    
    public static function pushMessage($event, $user_id, $target_id, $target_type, $data)
    {
        $redis = Redis::clientWithNoPrefix();

        $redis->publish(self::KEY_SMARTWORK_WEBSOCKET_MESSAGE, DXUtil::jsonEncode([
            'event' => $event,
            'user_id' => intval($user_id),
            'target_id' => intval($target_id),
            'target_type' => intval($target_type),
            'data' => $data,
            'time' => time()
        ]));
    }
    
    public static function processPushMessageForProjectUsers($project_id, $event_type, $target_id, $target_type, $data)
    {
        $user_id_list = ProjectMemberService::getProjectMemberUserIdList($project_id);
     
        foreach ($user_id_list as $user_id)
        {
            self::pushMessage($event_type, $user_id, $target_id, $target_type, $data);
        }
    }

    /**
     * @param $notification \app\models\Notification
     * @param $target_type
     */
    public static function pushMessageForNotification(&$notification, $target_type)
    {
        self::pushMessage(self::EVENT_NOTIFICATION, $notification->to_user_id, $notification->id, $target_type, DXUtil::jsonEncode($notification->attributes));
    }

    /**
     * @param $task \app\models\Task
     * @param $target_type
     */
    public static function pushMessageForTask(&$task, $target_type)
    {
        $project_id = $task->project_id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_DETAIL, $task->id, $target_type, DXUtil::jsonEncode($task->attributes));
    }

    /**
     * @param $task_member \app\models\TaskMember
     * @param $target_type
     */
    public static function pushMessageForTaskMember(&$task_member, $target_type)
    {
        $project_id = $task_member->project_id;

        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_MEMBER, $task_member->id, $target_type, DXUtil::jsonEncode($task_member->attributes));
    }
    
    /**
     * @param $group \app\models\TaskGroup | \app\models\Task
     * @param $target_type
     */
    public static function pushMessageForTaskGroup(&$group, $target_type)
    {
        $project_id = $group->project_id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_GROUP, $group->id, $target_type, DXUtil::jsonEncode($group->attributes));
    }

    /**
     * @param $project \app\models\Project
     * @param $target_type
     */
    public static function pushMessageForProject(&$project, $target_type)
    {
        $project_id = $project->id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_PROJECT, $project->id, $target_type, DXUtil::jsonEncode($project->attributes));
    }

    /**
     * @param $project_member \app\models\ProjectMember
     * @param $target_type
     */
    public static function pushMessageForProjectMember(&$project_member, $target_type)
    {
        self::pushMessage(self::EVENT_PROJECT, $project_member->user_id, $project_member->id, $target_type, DXUtil::jsonEncode($project_member->attributes));
    }
    
    /**
     * @param $task_comment \app\models\TaskComment
     * @param $target_type
     */
    public static function pushMessageForTaskComment(&$task_comment, $target_type)
    {
        $project_id = $task_comment->project_id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_COMMENT, $task_comment->id, $target_type, DXUtil::jsonEncode($task_comment->attributes));
    }

    /**
     * @param $project \app\models\Project
     * @param $target_type
     */
    public static function pushMessageForTaskGroupSortSave(&$project, $target_type)
    {
        $project_id = $project->id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_GROUP, $project->id, $target_type, DXUtil::jsonEncode($project->attributes));
    }
    
    /**
     * @param $group \app\models\TaskGroup
     * @param $target_type
     */
    public static function pushMessageForTaskSortSave(&$group, $target_type)
    {
        $project_id = $group->project_id;
        
        self::processPushMessageForProjectUsers($project_id, self::EVENT_TASK_GROUP, $group->id, $target_type, DXUtil::jsonEncode($group->attributes));
    }
}