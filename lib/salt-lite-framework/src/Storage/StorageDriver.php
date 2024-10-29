<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Storage;

enum StorageDriver: string
{
    case Local = 'local';
    case S3 = 's3';
}
