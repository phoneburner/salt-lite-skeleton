<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Tests\Behat;

use Behat\Behat\Context\Context;

class FeatureContext implements Context
{
    use HasApplicationLifecycle;
}
