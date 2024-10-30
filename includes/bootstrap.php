<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\App\BuildStage;
use PhoneBurner\SaltLite\Framework\App\EnvLoader;
use PhoneBurner\SaltLite\Framework\App\ErrorReporting;

use const PhoneBurner\SaltLite\Framework\APP_ROOT;

\define('PhoneBurner\SaltLite\Framework\START_MICROTIME', \microtime(true));
\define('PhoneBurner\SaltLite\Framework\APP_ROOT', \dirname(__DIR__));
\define('PhoneBurner\SaltLite\Framework\WEB_ROOT', APP_ROOT . '/html');

// Must check if the constant is already defined, as we define less strict defaults
// in the tests/bootstrap.php file, before executing this file.
if (! \defined('PhoneBurner\SaltLite\Framework\PASSWORD_ARGON2_OPTIONS')) {
    \define('PhoneBurner\SaltLite\Framework\PASSWORD_ARGON2_OPTIONS', [
        'memory_cost' => \PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => \PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'thread_cost' => \PASSWORD_ARGON2_DEFAULT_THREADS,
    ]);
}

// Override the server environmental variables with the .env file, if it exists.
EnvLoader::override(APP_ROOT . '/.env');

// Override the error reporting settings based on the environment configuration.
ErrorReporting::override($_ENV);

// Make sure that the build stage is defined, and default to production if not.
$_SERVER['SALT_BUILD_STAGE'] ??= $_ENV['SALT_BUILD_STAGE'] ??= BuildStage::Production->value;
