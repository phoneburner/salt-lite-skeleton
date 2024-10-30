<?php

declare(strict_types=1);

use function PhoneBurner\SaltLite\Framework\env;

use const PhoneBurner\SaltLite\Framework\APP_ROOT;

return [
    'database' => [
        'rabbitmq' => [
            'connections' => [
                'default' => [
                    'host' => env('SALT_RABBITMQ_HOST'),
                    'port' => (int)env('SALT_RABBITMQ_PORT', 5672),
                    'user' => env('SALT_RABBITMQ_USER'),
                    'password' => env('SALT_RABBITMQ_PASS'),
                ],
            ],
        ],
        'redis' => [
            'connections' => [
                'default' => [
                    'host' => env('SALT_REDIS_HOST'),
                    'port' => (int)env('SALT_REDIS_PORT', 6379),
                ],
            ],
            'timeout' => 5,
        ],
        'doctrine' => [
            'connections' => [
                'default' => [
                    'driver' => 'pdo_mysql',
                    'host' => env('SALT_MYSQL_HOST'),
                    'port' => (int)env('SALT_MYSQL_PORT', 3306),
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
                            'PhoneBurner\SaltLite\Migrations' => APP_ROOT . '/database/migrations',
                        ],
                    ],
                ],
            ],

        ],
    ],
];
