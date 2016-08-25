<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@app', dirname(__DIR__));

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
            ],
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'dix\base\component\RedisCache',
        ],
        'db' => $db,
    ],
    'params' => $params,
];
