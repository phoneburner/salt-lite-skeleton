<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Definition;

interface RouteGroupDefinitionProcessor
{
    public function __invoke(RouteGroupDefinition $definition): RouteGroupDefinition;
}
