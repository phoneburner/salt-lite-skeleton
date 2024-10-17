<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\App\App;
use PhoneBurner\SaltLiteFramework\App\Context;
use PhoneBurner\SaltLiteFramework\App\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';

App::bootstrap(Context::Http)->container->get(Kernel::class)->run();
