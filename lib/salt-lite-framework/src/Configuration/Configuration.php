<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Configuration;

interface Configuration
{
    /**
     * Gets a configuration value by key (dot notation),
     * returning null if no value is set.
     */
    public function get(string $key): mixed;
}
