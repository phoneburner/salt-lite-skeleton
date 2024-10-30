<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\App\App;
use PhoneBurner\SaltLite\Framework\App\Context;
use PhoneBurner\SaltLite\Framework\App\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';

App::bootstrap(Context::Http)->container->get(Kernel::class)->run();
