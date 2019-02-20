<?php


$dbConnector = [
    'meta' => [
        'entity_path' => [
            __DIR__ . '/../../app/Entity/'
        ],
        'auto_generate_proxies' => false,
        'proxy_dir' => __DIR__ . '/../app/Entity/Proxies',
        'cache' => null,
    ],
    'connection' => [
        //local
        'driver' => 'pdo_mysql', //pdo_pgsql  pdo_mysql
        'host' => 'localhost',
        'dbname' => 'project1',
        'user' => 'root',
        'password' => 'newpass',
        'charset' => 'UTF8',
        'options' => [
            "1001" => true
        ]
    ]
//server
];


