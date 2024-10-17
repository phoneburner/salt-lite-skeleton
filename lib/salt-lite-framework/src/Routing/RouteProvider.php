<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing;

use PhoneBurner\SaltLiteFramework\Routing\Definition\Definition;

interface RouteProvider
{
    /**
     * @return array<Definition>
     */
    public function __invoke(): array;
}
