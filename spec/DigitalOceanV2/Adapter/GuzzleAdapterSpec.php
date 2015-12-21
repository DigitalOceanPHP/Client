<?php

namespace spec\DigitalOceanV2\Adapter;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Prophecy\Argument;

class GuzzleAdapterSpec extends \PhpSpec\ObjectBehavior
{
    function let(Client $client)
    {
        $client->setDefaultOption('headers/Authorization', 'Bearer my_access_token')->willReturn($client);
        $client->setDefaultOption('events/request.complete', Argument::type('closure'))->shouldBeCalled();

        $this->beConstructedWith('my_access_token', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\GuzzleAdapter');
    }

    function it_returns_json_content($client, Request $request, Response $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_can_delete($client, Request $request, Response $response)
    {
        $client->delete('http://sbin.dk/123', ['foo' => 'bar'])->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->delete('http://sbin.dk/123', ['foo' => 'bar']);
    }

    function it_can_put($client, Request $request, Response $response)
    {
        $client->put('http://sbin.dk/456', ['content-type' => 'application/json'], '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['content-type' => 'application/json'], '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_can_post($client, Request $request, Response $response)
    {
        $client->post('http://sbin.dk/456', ['content-type' => 'application/json'], '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk/456', ['content-type' => 'application/json'], '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_returns_last_response_header($client, Request $request, Response $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');
        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
