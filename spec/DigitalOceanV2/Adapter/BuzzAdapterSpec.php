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
    /**
     * @param Browser $browser
     * @param MiddlewareInterface $middleware
     */
    function let($browser, $middleware)
    {
        $browser->addMiddleware($middleware)->shouldBeCalled();

        $this->beConstructedWith('my_access_token', $browser, $middleware);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\BuzzAdapter');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_returns_json_content($browser, $response, $stream)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_throws_an_http_exception($browser, $response, $stream)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(404);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 404))
            ->during('get', ['http://sbin.dk']);
    }

    function it_can_delete($browser, $response)
    {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->getStatusCode()->willReturn(200);

        $this->delete('http://sbin.dk/123');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_throws_an_http_exception_if_cannot_delete($browser, $response, $stream) {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('delete', ['http://sbin.dk/123']);
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_can_put_basic($browser, $response, $stream)
    {
        $browser->put('http://sbin.dk/456', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('foo');

        $this->put('http://sbin.dk/456')->shouldBe('foo');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_can_put_array($browser, $response, $stream)
    {
        $browser->put('http://sbin.dk/456', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_throws_an_http_exception_if_cannot_put($browser, $response, $stream) {
        $browser->put('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('put', ['http://sbin.dk', ['foo' => 'bar']]);
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_can_post_basic($browser, $response, $stream)
    {
        $browser->post('http://sbin.dk', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('foo');

        $this->post('http://sbin.dk')->shouldBe('foo');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_can_post_array($browser, $response, $stream)
    {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     * @param StreamInterface $stream
     */
    function it_throws_an_http_exception_if_cannot_post($browser, $response, $stream) {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('post', ['http://sbin.dk', ['foo' => 'bar']]);
    }

    /**
     * @param Browser $browser
     * @param ResponseInterface $response
     */
    function it_returns_last_response_header($browser, $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
