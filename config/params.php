<?php

$params_extra = require(__DIR__ . '/params_extra.php');

$base = [
    'version' => '0.0.1',

    'redis-param' => [
        'scheme' => 'tcp',
        'host'   => 'redis.duapp.com',
        'port'   => 6379,
        'password' => '61438c2c4dfab85640fcc0b738f05c49Hide',
        'read_write_timeout' => 0,
        'database' => 'hTxMSSKPqJTVYZoUJGLB',
    ],

    'redis-option' => [
        'prefix' => 'smartwork.hrm.api.',
    ],

    'redis-key-prefix' => 'smartwork.hrm.api.'

];

return array_merge($base, $params_extra);
