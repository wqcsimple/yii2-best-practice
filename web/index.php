<?php

defined('RUN_START_TIME') or define('RUN_START_TIME', microtime(true));
defined('RUN_START_TIME_INT') or define('RUN_START_TIME_INT', intval(RUN_START_TIME));

// comment out the following two lines when deployed to production
if ($_SERVER['HTTP_HOST'] == 'data.collection' || isset($_GET['debug']))
{
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require('util.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
