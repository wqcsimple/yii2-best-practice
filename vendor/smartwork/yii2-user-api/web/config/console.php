<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@smartwork/user/api', './');


$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'generator'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'generator' => [
            'class' => 'dix\base\module\generator\Module',
            'config' => [
                'user' => [
                    'template' => 'api',
                    'actions' => \smartwork\user\api\data\Api::ActionList(),
                    'controllerPath' => 'smartwork\user\api\controller',
                    'baseController' => 'smartwork\user\api\controller\BaseApiController',
                ],
            ],
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'dix\base\component\RedisCache;',
        ],
        'db' => $db,
    ],
    'params' => $params,
];
