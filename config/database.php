<?php

declare(strict_types=1);

use function PhoneBurner\SaltLiteFramework\env;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

return [
    'database' => [
        'redis' => [
            'connections' => [
                'default' => [
                    'host' => env('SALT_REDIS_CACHE_HOST'),
                    'port' => env('SALT_REDIS_CACHE_PORT', 6379),
                ],
            ],
            'timeout' => 5,
        ],
        'doctrine' => [
            'connections' => [
                'default' => [
                    'driver' => 'pdo_mysql',
                    'host' => env('SALT_MYSQL_HOST'),
                    'port' => env('SALT_MYSQL_PORT', 3306),
                    'dbname' => env('SALT_MYSQL_NAME'),
                    'user' => env('SALT_MYSQL_USER'),
                    'password' => env('SALT_MYSQL_PASS'),
                    'charset' => 'utf8mb4',
                    'entity_manager' => [
                        'entity_paths' => [
                            APP_ROOT . '/src/',
                        ],
                        'cache_path' => APP_ROOT . '/storage/doctrine/default/',
                        'cache_driver' => [
                            'metadata' => env('SALT_DOCTRINE_METADATA_CACHE_DRIVER'),
                            'query' => env('SALT_DOCTRINE_QUERY_CACHE_DRIVER'),
                            'result' => env('SALT_DOCTRINE_RESULT_CACHE_DRIVER'),
                            'entity' => env('SALT_DOCTRINE_ENTITY_CACHE_DRIVER'),
                        ],
                        'event_subscribers' => [],
                    ],
                    'migrations' => [
                        'table_storage' => [
                            'table_name' => 'doctrine_migration_versions',
                        ],
                        'migrations_paths' => [
                            'PhoneBurner\SaltLiteSkeleton\Migrations' => APP_ROOT . '/database/migrations',
                        ],
                    ],
                ],
            ],

        ],
    ],
];
