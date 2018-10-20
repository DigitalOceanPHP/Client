<?php

namespace spec\DigitalOceanV2\Adapter;

use Buzz\Browser;
use Buzz\Middleware\MiddlewareInterface;
use DigitalOceanV2\Exception\HttpException;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class BuzzAdapterSpec extends ObjectBehavior
{
    function let(Browser $browser, MiddlewareInterface $middleware)
    {
        $browser->addMiddleware($middleware)->shouldBeCalled();

        $this->beConstructedWith('my_access_token', $browser, $middleware);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\BuzzAdapter');
    }

    function it_returns_json_content(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(404);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 404))
            ->during('get', ['http://sbin.dk']);
    }

    function it_can_delete(Browser $browser, ResponseInterface $response)
    {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->getStatusCode()->willReturn(200);

        $this->delete('http://sbin.dk/123');
    }

    function it_throws_an_http_exception_if_cannot_delete(
        Browser $browser,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('delete', ['http://sbin.dk/123']);
    }

    function it_can_put_basic(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->put('http://sbin.dk/456', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('foo');

        $this->put('http://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_array(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->put('http://sbin.dk/456', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_put(
        Browser $browser,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $browser->put('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('put', ['http://sbin.dk', ['foo' => 'bar']]);
    }

    function it_can_post_basic(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->post('http://sbin.dk', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('foo');

        $this->post('http://sbin.dk')->shouldBe('foo');
    }

    function it_can_post_array(Browser $browser, ResponseInterface $response, StreamInterface $stream)
    {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_post(
        Browser $browser,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('post', ['http://sbin.dk', ['foo' => 'bar']]);
    }

    function it_returns_last_response_header(Browser $browser, ResponseInterface $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
