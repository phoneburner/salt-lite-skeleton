<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router;

use PhoneBurner\SaltLiteFramework\Router\Definition\Definition;

interface RouteProvider
{
    /**
     * @return array<Definition>
     */
    public function __invoke(): array;
}
