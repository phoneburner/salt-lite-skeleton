<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Tests\Unit\TestSupport;

use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

use function PhoneBurner\SaltLite\Framework\path;

class OpenApiValidator
{
    private static CacheItemPoolInterface $cache;

    public static function assert(
        RequestInterface $request,
        ResponseInterface|null $response,
        string $api_spec = 'openapi.yaml',
    ): void {
        TestCase::assertInstanceOf(ResponseInterface::class, $response);

        $validator_builder = new ValidatorBuilder()
            ->fromYamlFile(path('/' . \ltrim($api_spec, '/')))
            ->setCache(self::$cache ??= new ArrayAdapter(storeSerialized: false));

        $validator_builder->getResponseValidator()->validate(
            $validator_builder->getRequestValidator()->validate($request),
            $response,
        );
    }
}
