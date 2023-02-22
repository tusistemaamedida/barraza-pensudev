<?php

use Illuminate\Support\Str;

return [

    'default' => env('DB_CONNECTION', 'pensu_sdev'),

    'connections' => [
        'pensu_sdev' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', null),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'envasado' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_ENVASADO', 'localhost'),
            'port' => env('DB_PORT_ENVASADO', null),
            'database' => env('DB_DATABASE_ENVASADO', 'forge'),
            'username' => env('DB_USERNAME_ENVASADO', 'forge'),
            'password' => env('DB_PASSWORD_ENVASADO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'pedidos' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_PEDIDOS', 'localhost'),
            'port' => env('DB_PORT_PEDIDOS', null),
            'database' => env('DB_DATABASE_PEDIDOS', 'forge'),
            'username' => env('DB_USERNAME_PEDIDOS', 'forge'),
            'password' => env('DB_PASSWORD_PEDIDOS', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    'migrations' => 'migrations',
    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
