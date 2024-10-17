<?php

declare(strict_types=1);

// This constant must be defined before including the vendor autoload file so that
// it is defined before the SaltLiteFramework constants are defined in the
// application bootstrap file.
use PhoneBurner\SaltLiteFramework\App\Context;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

//define('PhoneBurner\SaltLiteFramework\PASSWORD_ARGON2_OPTIONS', [
//    'memory_cost' => 32,
//    'time_cost' => 1,
//    'thread_cost' => 1,
//]);

define('PhoneBurner\SaltLiteFramework\CONTEXT', Context::Test);

define('PhoneBurner\SaltLiteFramework\UNIT_TEST_ROOT', APP_ROOT . '/lib/salt-lite-framework/tests');

define('PhoneBurner\SaltLiteSkeleton\UNIT_TEST_ROOT', APP_ROOT . '/tests/unit');
