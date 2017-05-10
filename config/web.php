<?php

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
//                'm/agreement' => '/site/agreement',
//                'm/contact' => '/site/contact',

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
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=data_collection',
            'username' => 'root',
            'password' => '7',
            'charset' => 'utf8mb4',
        ],
    ],
    'modules' => [
        'admin-100' => [
            'class' => 'app\modules\admin\v100\Module',
        ],
        'client-100' => [
            'class' => 'app\modules\client\v100\Module',
        ],
    ],
];

/**
 * usage
 * $config = DXUtil::param('redis-param');
 */

$config['params'] = [
    'version' => '0.0.1',

    'alipay' => [
        'partner' => '',   
        'seller_id' => '',
        'notify_url' => '{url}/pay/alipay-notify', // {url} 需要替换
        'rsa_private_key' => '../pay/alipay/key/rsa_private_key.pem',
        'subject' => '',
        'body' => ''
    ],

    'wxpay' => [
        'appid' => '',
        'partnerid' => '',
        'notify_url' => '{url}/pay/wxpay-notify-yunto' // {url} 需要替换
    ],


    'redis-param' => [
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
        'password' => null,
        'read_write_timeout' => 0,
        'database' => 0,
    ],

    'redis-option' => [
        'prefix' => 'yii2.best.api.',
    ],

    'redis-key-prefix' => 'yii2.best.api.'
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
