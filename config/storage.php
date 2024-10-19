<?php

declare(strict_types=1);

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter;

use function PhoneBurner\SaltLiteFramework\env;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

return [
    'storage' => [
        'default' => env('SALT_STORAGE_DRIVER') ?: 'local',
        'drivers' => [
            'local' => [
                'adapter' => LocalFilesystemAdapter::class,
                'root' => APP_ROOT . '/storage/app',
            ],
            's3' => [
                'adapter' => AwsS3V3Adapter::class,
                'client' => [
                    'credentials' => [
                        'key' => env('SALT_AWS_S3_ACCESS_KEY_ID'),
                        'secret' => env('SALT_AWS_S3_SECRET_ACCESS_KEY'),
                    ],
                    'region' => env('SALT_AWS_S3_DEFAULT_REGION') ?: 'us-west-1',
                    'signature' => 'v4',
                    'version' => 'latest',
                ],
                'bucket-name' => env('SALT_AWS_S3_BUCKET_NAME'),
                'prefix' => env('SALT_AWS_S3_PATH_PREFIX') ?: null,
            ],
        ],
    ],
];
