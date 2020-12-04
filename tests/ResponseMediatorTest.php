<?php

declare(strict_types=1);

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class ResponseMediatorTest extends TestCase
{
    public function testGetContent(): void
    {
        $response = new Response(
            200,
            ['Content-Type' => ['application/json']],
            Utils::streamFor('{"foo": "bar"}')
        );

        $this->assertEquals((object) ['foo' => 'bar'], ResponseMediator::getContent($response));
    }

    public function testGetContentNotJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => ['text/html']],
            Utils::streamFor($body)
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The content type was not application/json.');

        ResponseMediator::getContent($response);
    }

    public function testGetContentInvalidJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => ['application/json']],
            Utils::streamFor($body)
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('json_decode error: Syntax error');

        ResponseMediator::getContent($response);
    }

    public function testGetErrrorMessageInvalidJson(): void
    {
        $body = 'foobar';
        $response = new Response(
            200,
            ['Content-Type' => ['application/json']],
            Utils::streamFor($body)
        );

        $this->assertNull(ResponseMediator::getErrorMessage($response));
    }
}
