<?php

namespace spec\DigitalOceanV2\Adapter;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class GuzzleAdapterSpec extends \PhpSpec\ObjectBehavior
{
    function let(Client $client, Request $request, Response $response)
    {
        $this->beConstructedWith('my_access_token', null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\GuzzleAdapter');
    }

    function it_returns_json_content($client, $request, $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        //$this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception($client, $request, $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(404);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \RuntimeException('[404] error message (error id)'))->duringGet('http://sbin.dk');
    }

    function it_throws_an_custom_exception($client, $request, $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(404);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \DigitalOceanV2\Exception\ResponseException('[404] error message (error id)'))->duringGet('http://sbin.dk');
    }

    function it_can_delete($client, $request, $response)
    {
        $client->delete('http://sbin.dk/123', array('foo' => 'bar'))->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);

        //$this->delete('http://sbin.dk/123', array('foo' => 'bar'));
    }

    function it_throws_an_runtime_exception_if_cannot_delete($client, $request, $response)
    {
        $client->delete('http://sbin.dk/123', array('foo' => 'bar'))->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringDelete('http://sbin.dk/123');
    }

    function it_throws_an_custom_exception_if_cannot_delete($client, $request, $response)
    {
        $client->delete('http://sbin.dk/123', array('foo' => 'bar'))->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \DigitalOceanV2\Exception\ResponseException('[500] error message (error id)'))->duringDelete('http://sbin.dk/123');
    }

    function it_can_put($client, $request, $response)
    {
        $client->put('http://sbin.dk/456', array('foo' => 'bar'), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        //$this->put('http://sbin.dk/456', array('foo' => 'bar'), '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception_if_cannot_update($client, $request, $response)
    {
        $client->put('http://sbin.dk/456', array('foo' => 'bar'), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringPut('http://sbin.dk', array(), '{"foo":"bar"}');
    }

    function it_throws_an_custom_exception_if_cannot_update($client, $request, $response)
    {
        $client->put('http://sbin.dk/456', array('foo' => 'bar'), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \DigitalOceanV2\Exception\ResponseException('[500] error message (error id)'))->duringPut('http://sbin.dk', array(), '{"foo":"bar"}');
    }

    function it_can_post($client, $request, $response)
    {
        $client->post('http://sbin.dk', array('foo' => 'bar'), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        //$this->post('http://sbin.dk', array('foo' => 'bar'), '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception_if_cannot_create($client, $request, $response)
    {
        $client->post('http://sbin.dk', array(), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringPost('http://sbin.dk', array(), '{"foo":"bar"}');
    }

    function it_throws_an_custom_exception_if_cannot_create($client, $request, $response)
    {
        $client->post('http://sbin.dk', array(), '{"foo":"bar"}')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getBody(true)->willReturn('{"id":"error id", "message":"error message"}');

        //$this->shouldThrow(new \DigitalOceanV2\Exception\ResponseException('[500] error message (error id)'))->duringPost('http://sbin.dk', array(), '{"foo":"bar"}');
    }

    function it_returns_last_response_header($client, $request, $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        //$this->getLatestResponseHeaders()->shouldBeArray();
        //$this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
