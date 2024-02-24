<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('STACKHERO_MYSQL_HOST'),
            'port' => 3306,
            'username' => env('STACKHERO_MYSQL_USER'),
            'password' => env('STACKHERO_MYSQL_ROOT_PASSWORD'),
            'database' => env('STACKHERO_MYSQL_USER'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'sslmode' => 'require',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => 'isrgrootx1.pem',
                // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ]) : [],
        ],

    ],

    'migrations' => 'migrations',

];
