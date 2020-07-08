<?php

declare(strict_types=1);

namespace spec\DigitalOceanV2\HttpClient;

use Buzz\Browser;
use Buzz\Message\Response;
use Buzz\Middleware\MiddlewareInterface;
use DigitalOceanV2\Exception\HttpException;

class BuzzHttpClientSpec extends \PhpSpec\ObjectBehavior
{
    function let(Browser $browser)
    {
        $this->beConstructedWith($browser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\HttpClient\BuzzHttpClient');
    }

    function it_returns_json_content(Browser $browser, Response $response)
    {
        $browser->get('https://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->get('https://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception(Browser $browser, Response $response)
    {
        $browser->get('https://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(404);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 404))
            ->during('get', ['https://sbin.dk']);
    }

    function it_can_delete(Browser $browser, Response $response)
    {
        $browser->delete('https://sbin.dk/456', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('foo');

        $this->delete('https://sbin.dk/456')->shouldBe('foo');
    }

    function it_throws_an_http_exception_if_cannot_delete(Browser $browser, Response $response)
    {
        $browser->delete('https://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('delete', ['https://sbin.dk', ['foo' => 'bar']]);
    }

    function it_can_put_basic(Browser $browser, Response $response)
    {
        $browser->put('https://sbin.dk/456', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('foo');

        $this->put('https://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_array(Browser $browser, Response $response)
    {
        $browser->put('https://sbin.dk/456', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->put('https://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_put(Browser $browser, Response $response)
    {
        $browser->put('https://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('put', ['https://sbin.dk', ['foo' => 'bar']]);
    }

    function it_can_post_basic(Browser $browser, Response $response)
    {
        $browser->post('https://sbin.dk', [], '')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('foo');

        $this->post('https://sbin.dk')->shouldBe('foo');
    }

    function it_can_post_array(Browser $browser, Response $response)
    {
        $browser->post('https://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->post('https://sbin.dk', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_post(Browser $browser, Response $response)
    {
        $browser->post('https://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')
            ->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))
            ->during('post', ['https://sbin.dk', ['foo' => 'bar']]);
    }

    function it_returns_last_response_header(Browser $browser, Response $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit', false)->willReturn(['1200']);
        $response->getHeader('RateLimit-Remaining', false)->willReturn(['1100']);
        $response->getHeader('RateLimit-Reset', false)->willReturn(['1402425459']);

        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }

    function it_returns_null_last_response_header(Browser $browser, Response $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit', false)->willReturn(null);
        $response->getHeader('RateLimit-Remaining', false)->willReturn(['1100']);
        $response->getHeader('RateLimit-Reset', false)->willReturn(['1402425459']);

        $this->getLatestResponseHeaders()->shouldBeNull();
    }
}
