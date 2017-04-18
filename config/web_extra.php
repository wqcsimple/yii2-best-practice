<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=data_collection',
            'username' => 'root',
            'password' => '7',
            'charset' => 'utf8mb4',
        ],
    ],
    
    
    'params' => [
        'redis-param' => [
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
            'password' => null,
            'read_write_timeout' => 0,
            'database' => 0,
        ],
    ]

];
