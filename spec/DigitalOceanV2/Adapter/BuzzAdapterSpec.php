<?php

namespace spec\DigitalOceanV2\Adapter;

use Buzz\Browser;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\Response;
use DigitalOceanV2\Exception\HttpException;

class BuzzAdapterSpec extends \PhpSpec\ObjectBehavior
{
    function let(Browser $browser, ListenerInterface $listener)
    {
        $browser->addListener($listener)->shouldBeCalled();

        $this->beConstructedWith('my_access_token', $browser, $listener);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\BuzzAdapter');
    }

    function it_returns_json_content($browser, Response $response)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception($browser, Response $response)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(404);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 404))->duringGet('http://sbin.dk');
    }

    function it_can_delete($browser, Response $response)
    {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->isSuccessful()->willReturn(true);

        $this->delete('http://sbin.dk/123');
    }

    function it_throws_an_http_exception_if_cannot_delete($browser, Response $response)
    {
        $browser->delete('http://sbin.dk/123')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))->duringDelete('http://sbin.dk/123');
    }

    function it_can_put_basic($browser, Response $response)
    {
        $browser->put('http://sbin.dk/456', [], '')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('foo');

        $this->put('http://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_array($browser, Response $response)
    {
        $browser->put('http://sbin.dk/456', ['Content-Type: application/json'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_put($browser, Response $response)
    {
        $browser->put('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))->duringPut('http://sbin.dk', ['foo' => 'bar']);
    }

    function it_can_post_basic($browser, Response $response)
    {
        $browser->post('http://sbin.dk', [], '')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('foo');

        $this->post('http://sbin.dk')->shouldBe('foo');
    }

    function it_can_post_array($browser, Response $response)
    {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_http_exception_if_cannot_post($browser, Response $response)
    {
        $browser->post('http://sbin.dk', ['Content-Type: application/json'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error_id", "message":"Error message."}');

        $this->shouldThrow(new HttpException('Error message.', 500))->duringPost('http://sbin.dk', ['foo' => 'bar']);
    }

    function it_returns_last_response_header($browser, Response $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
