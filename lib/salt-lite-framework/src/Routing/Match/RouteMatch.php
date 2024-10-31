<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Routing\Match;

use PhoneBurner\Http\Message\UriWrapper;
use PhoneBurner\SaltLite\Framework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLite\Framework\Routing\RequestHandler\NullHandler;
use PhoneBurner\SaltLite\Framework\Routing\Route;
use PhoneBurner\SaltLite\Framework\Util\Helper\Arr;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMatch implements Route
{
    use UriWrapper;

    public static function make(RouteDefinition $definition, array $path_vars = []): self
    {
        // the route definition provides ways to set these, but they aren't
        // required
        $attributes = [
            RequestHandlerInterface::class => NullHandler::class,
            MiddlewareInterface::class => [],
            ...$definition->getAttributes(),
        ];

        // if this was already set, ensure it's an array
        $attributes[MiddlewareInterface::class] = Arr::wrap($attributes[MiddlewareInterface::class]);

        return new self(
            $definition
                ->withAttributes($attributes)
                // order here is important, path params are not preserved when
                // evolving the definition, only when calling
                // `withPathParameter()` or `withPathParameters()`
                ->withPathParameters($path_vars),
            $path_vars,
        );
    }

    private function __construct(private readonly RouteDefinition $definition, private readonly array $path_vars)
    {
        $this->setWrapped($this->definition->getWrapped());
    }

    public function getAttributes(): array
    {
        return $this->definition->getAttributes();
    }

    public function getPathParameters(): array
    {
        return $this->path_vars;
    }

    public function getPathParameter(string $name, mixed $default = null): mixed
    {
        return $this->path_vars[$name] ?? $default;
    }

    /**
     * @return class-string<RequestHandlerInterface>
     */
    public function getHandler(): string
    {
        return $this->getAttributes()[RequestHandlerInterface::class];
    }

    /**
     * @return array<class-string<MiddlewareInterface>>
     */
    public function getMiddleware(): array
    {
        return $this->getAttributes()[MiddlewareInterface::class];
    }

    #[\Override]
    public function withPathParameter(string $name, string $value): self
    {
        return new self($this->definition->withPathParameter($name, $value), $this->path_vars);
    }

    #[\Override]
    public function withPathParameters(array $params): self
    {
        return new self($this->definition->withPathParameters($params), $this->path_vars);
    }

    protected function wrap(UriInterface $uri): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withScheme(string $scheme): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withUserInfo(string $user, ?string $password = null): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withHost(string $host): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withPort(?int $port): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withPath(string $path): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withQuery(string $query): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }

    public function withFragment(string $fragment): never
    {
        throw new \LogicException(self::class . ' does not support URI with methods directly, use `getWrapped()` to get the underlying URI');
    }
}
