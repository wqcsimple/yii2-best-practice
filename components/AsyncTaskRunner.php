<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/16/17
 * Time: 11:50 AM
 */
namespace app\components;

use app\services\SearchIndexService;
use dix\base\component\Redis;

class AsyncTaskRunner extends \yii\base\Object
{
    private static $registry = [];
    
    public static function boot()
    {
        SearchIndexService::registerAsyncTask();
    }

    public static function registerAsyncTask($entry)
    {
        $entry_list = [];
        if (DXUtil::isAssocArray($entry))
        {
            $entry_list[] = $entry;
        }
        else if (count($entry) > 0)
        {
            $entry_list = $entry;
        }

        foreach ($entry_list as $entry)
        {
            $route = $entry['route'];
            $function = $entry['function'];
            $call = $entry['call'];
            $key = self::getKey($route, $function);
            self::$registry[$key] = $call;
        }
    }

    private static function getKey($route, $function)
    {
        return "${route}#${function}";
    }

    public static function submitTask($route, $function, $params)
    {
        $redis = Redis::client();

        $task = [
            'time' => DXUtil::time(),
            'route' => $route,
            'function' => $function,
            'params' => $params
        ];

        DXUtil::consoleLog("submit task: " . json_encode($task));

        $redis->lpush(DXKey::getKeyOfRunnerTaskListPending(), json_encode($task));
    }

    public static function runTask($task)
    {
        if (!self::validateTask($task))
        {
            return;
        }

        $redis = Redis::client();

        $route = $task['route'];
        $function = $task['function'];
        $params = $task['params'];
        $key = self::getKey($route, $function);

        if (isset(self::$registry[$key]))
        {
            $func = self::$registry[$key];
            if (is_callable($func))
            {
                try
                {
                    call_user_func_array($func, $params);
                }
                catch (\Exception $e)
                {
                    DXUtil::consoleLog($e->getMessage() . "\n" . $e->getTraceAsString());
                    $redis->lpush(DXKey::getKeyOfRunnerTaskListFail(), [json_encode($task)]);
                }
            }
            else
            {
                DXUtil::consoleLog("func $func is not callable");
            }
        }
        else
        {
            DXUtil::consoleLog("entry not exists");
        }
    }

    public static function validateTask($task)
    {
        return DXUtil::checkArrayKeys($task, ['time', 'route', 'function', 'params']);
    }
}
