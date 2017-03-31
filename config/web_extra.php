<?php

return [
    'params' => [
        'redis-param' => [
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
            'password' => null,
            'read_write_timeout' => 0,
            'database' => 0,
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=whis',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ],
    ]

];
