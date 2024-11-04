<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Tests\Routing\Domain;

use PhoneBurner\SaltLite\Framework\Http\Domain\ContentType;
use PhoneBurner\SaltLite\Framework\Routing\Domain\StaticFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StaticFileTest extends TestCase
{
    #[Test]
    public function happy_path_is_happy(): void
    {
        $path = '/path/to/file';
        $content_type = ContentType::TEXT;

        $static_file = new StaticFile($path, $content_type);

        self::assertSame($path, $static_file->path);
        self::assertSame($content_type, $static_file->content_type);
    }
}
