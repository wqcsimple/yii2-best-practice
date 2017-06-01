<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            
//            'dsn' => 'mysql:host=172.17.0.4;dbname=blog',
            'dsn' => 'mysql:host=db;dbname=data_collection',
            
            'username' => 'root',
            'password' => '7',
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
                ['dsn' => 'mysql:host=172.17.0.5;dbname=blog'],
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
        
//        'redis-param' => [
//            'scheme' => 'tcp',
//            'host'   => '10.27.103.127',
//            'port'   => 6379,
//            'password' => 'xhOvJHMMvaCORETRZeHEySWdcO9wBYE',
//            'read_write_timeout' => 0,
//            'database' => 0,
//        ],
        
        'elasticsearch' => [
            'hosts' => [
                '106.14.135.129:9200'
            ]
        ],
    ]
    

];
