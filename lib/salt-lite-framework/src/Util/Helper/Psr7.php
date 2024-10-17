<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Util\Helper;

use PhoneBurner\SaltLiteFramework\Http\Domain\ContentType;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpHeader;
use Psr\Http\Message\MessageInterface;

class Psr7
{
    public static function expectsJson(MessageInterface $message): bool
    {
        return \str_contains(\strtolower($message->getHeaderLine(HttpHeader::ACCEPT)), 'json')
            || \str_contains(\strtolower($message->getHeaderLine(HttpHeader::CONTENT_TYPE)), 'json');
    }

    public static function expectsHtml(MessageInterface $message): bool
    {
        return \str_contains(\strtolower($message->getHeaderLine(HttpHeader::ACCEPT)), ContentType::HTML)
            || \str_contains(\strtolower($message->getHeaderLine(HttpHeader::CONTENT_TYPE)), ContentType::HTML);
    }
}
