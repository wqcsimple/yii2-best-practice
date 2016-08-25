<?php

return [
    'mq-host' => 'rabbitmq',
    'mq-port' => 5672,
    'mq-username' => 'express',
    'mq-password' => 'express@2014',
    'mq-virtual-host' => 'express',

    'redis-param' => [
        'scheme' => 'tcp',
        'host'   => 'redis',
        'port'   => 6379,
        'password' => null,
        'read_write_timeout' => 0,
        'database' => 0,
    ],

];
