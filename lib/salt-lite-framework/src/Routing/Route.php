<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing;

use Psr\Http\Message\UriInterface;

/**
 * General route interface allowing a URI to be generated from a route given
 * parameters. Concrete implementations provide additional contextual behaviour.
 */
interface Route extends UriInterface
{
    public function withPathParameter(string $name, string $value): self;

    public function withPathParameters(array $params): self;
}
