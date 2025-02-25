<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => 3306,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'sslmode' => 'require',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ]) : [],
        ],

    ],

    'migrations' => 'migrations',

];
