<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => '/site/index',
    'components' => [
        'session' => [
            'class' => 'dix\base\component\RedisSession',
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'hmsi6ma9',
        ],
        'cache' => [
            'class' => 'dix\base\component\RedisCache',
        ],
        'urlManager' => [
            'rules' => [
                'm/agreement' => '/site/agreement',
                'm/contact' => '/site/contact',

                [
                     'class' => 'dix\base\component\ModuleApiUrlRule',
                ],

                // '<controller:.+>/<id:\d+>' => '<controller>/view',
                // '<controller:.+>/<action:.+>/<id:\d+>' => '<controller>/<action>',
                // '<controller:.+>/<action:.+>' => '<controller>/<action>',

            ],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info', 'trace', 'profile'] : ['error', 'warning'],
                    'exportInterval' => YII_DEBUG ? 1 : 100,
                ],
                [
                    'class' => 'dix\base\component\FluentLogTarget',
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info', 'trace', 'profile'] : ['error', 'warning'],
                    'exportInterval' => YII_DEBUG ? 1 : 100,
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'admin-100' => [
            'class' => 'app\modules\admin\v100\Module',
        ],
        'client-100' => [
            'class' => 'app\modules\client\v100\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV)
{
    // configuration adjustments for 'dev' environment
     $config['bootstrap'][] = 'debug';
     $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
