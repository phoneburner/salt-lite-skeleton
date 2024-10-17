<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Router\RequestHandler;

use PhoneBurner\SaltLiteFramework\Http\Domain\ContentType;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpHeader;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\FileNotFoundResponse;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\ServerErrorResponse;
use PhoneBurner\SaltLiteFramework\Http\Response\StreamResponse;
use PhoneBurner\SaltLiteFramework\Router\Domain\StaticFile;
use PhoneBurner\SaltLiteFramework\Router\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Router\RequestHandler\StaticFileRequestHandler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use const PhoneBurner\SaltLiteFramework\UNIT_TEST_ROOT;

class StaticFileRequestHandlerTest extends TestCase
{
    protected const string GOOD_FILE = UNIT_TEST_ROOT . '/Fixtures/2500x2500.png';

    private StaticFileRequestHandler $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new StaticFileRequestHandler();
    }

    #[Test]
    public function missing_route_match_returns_server_error_response(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with(RouteMatch::class)
            ->willReturn(null);

        $response = $this->sut->handle($request);

        self::assertInstanceOf(ServerErrorResponse::class, $response);
    }

    #[DataProvider('providesInvalidStaticFile')]
    #[Test]
    public function invalid_static_file_returns_server_error_response(array $attributes): void
    {
        $route_match = $this->createMock(RouteMatch::class);
        $route_match->method('getAttributes')->willReturn($attributes);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with(RouteMatch::class)
            ->willReturn($route_match);

        $response = $this->sut->handle($request);

        self::assertInstanceOf(ServerErrorResponse::class, $response);
    }

    public static function providesInvalidStaticFile(): array
    {
        return [
            [[]],
            [[StaticFile::class => new \stdClass()]],
        ];
    }

    #[Test]
    public function bad_file_returns_file_not_found_response(): void
    {
        $route_match = $this->createMock(RouteMatch::class);
        $route_match->method('getAttributes')->willReturn([
            StaticFile::class => new StaticFile('bad_file', ContentType::HTML),
        ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with(RouteMatch::class)
            ->willReturn($route_match);

        $response = $this->sut->handle($request);

        self::assertInstanceOf(FileNotFoundResponse::class, $response);
    }

    #[Test]
    public function valid_static_file_returns_inline_stream_response(): void
    {
        $route_match = $this->createMock(RouteMatch::class);
        $route_match->method('getAttributes')->willReturn([
            StaticFile::class => new StaticFile(self::GOOD_FILE, ContentType::PNG),
        ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with(RouteMatch::class)
            ->willReturn($route_match);

        $response = $this->sut->handle($request);

        self::assertInstanceOf(StreamResponse::class, $response);
        self::assertSame(ContentType::PNG, $response->getHeaderLine(HttpHeader::CONTENT_TYPE));
        self::assertSame((string)\filesize(self::GOOD_FILE), $response->getHeaderLine(HttpHeader::CONTENT_LENGTH));
        self::assertSame('inline', $response->getHeaderLine(HttpHeader::CONTENT_DISPOSITION));
        self::assertStringEqualsFile(self::GOOD_FILE, (string)$response->getBody());
    }

    #[Test]
    public function valid_static_file_returns_attachment_stream_response(): void
    {
        $route_match = $this->createMock(RouteMatch::class);
        $route_match->method('getAttributes')->willReturn([
            StaticFile::class => new StaticFile(self::GOOD_FILE, ContentType::PNG),
            HttpHeader::CONTENT_DISPOSITION => 'attachment',
        ]);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->with(RouteMatch::class)
            ->willReturn($route_match);

        $response = $this->sut->handle($request);

        self::assertInstanceOf(StreamResponse::class, $response);
        self::assertSame(ContentType::PNG, $response->getHeaderLine(HttpHeader::CONTENT_TYPE));
        self::assertSame((string)\filesize(self::GOOD_FILE), $response->getHeaderLine(HttpHeader::CONTENT_LENGTH));
        self::assertSame('attachment', $response->getHeaderLine(HttpHeader::CONTENT_DISPOSITION));
        self::assertStringEqualsFile(self::GOOD_FILE, (string)$response->getBody());
    }
}
