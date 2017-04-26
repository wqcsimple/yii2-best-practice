<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            
            'dsn' => 'mysql:host=db;dbname=data_collection',
            
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',

            'slaveConfig' => [
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8mb4',
                'attributes' => [
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],

            'slaves' => [
                ['dsn' => 'mysql:host=db;dbname=yunto'],
            ],
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
