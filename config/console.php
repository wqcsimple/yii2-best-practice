<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@app', dirname(__DIR__));

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'generator'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'generator' => [
            'class' => 'dix\base\module\generator\Module',
            'config' => [
                'admin' => [
                    'template' => 'api',
                    'actions' => \app\modules\admin\v100\data\Api::ActionList(),
                    'controllerPath' => 'app\modules\admin\v100\controllers',
                    'baseController' => 'app\modules\admin\v100\controllers\BaseApiController',
                ],
                'client' => [
                    'template' => 'api',
                    'actions' => \app\modules\client\v100\data\Api::ActionList(),
                    'controllerPath' => 'app\modules\client\v100\controllers',
                    'baseController' => 'app\modules\client\v100\controllers\BaseApiController',
                ],
                'blog' => [
                    'template' => 'api',
                    'actions' => \app\modules\blog\v100\data\Api::ActionList(),
                    'controllerPath' => 'app\modules\blog\v100\controllers',
                    'baseController' => 'app\modules\blog\v100\controllers\BaseApiController',
                ],
            ],
        ],
    ],
    'components' => [
        'errorHandler' => ['class' => 'app\components\ConsoleErrorHandler'],
        'cache' => [
            'class' => 'dix\base\component\RedisCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=whis',
            'username' => 'root',
            'password' => '7',
            'charset' => 'utf8mb4',
        ]
    ],
];

$config['params'] = [
    'version' => '0.0.1',

    'redis-param' => [
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
        'password' => null,
        'read_write_timeout' => 0,
        'database' => 0,
    ],

    'redis-option' => [
         'prefix' => 'whis.best.api.',
    ],
];


if (YII_ENV_DEV)
{
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

$config_extra = require(__DIR__ . '/web_extra.php');

$config = array_replace_recursive($config, $config_extra);

return $config;
