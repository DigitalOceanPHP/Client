<?php

namespace spec\DigitalOceanV2\Adapter;

use DigitalOceanV2\Adapter\GuzzleHttpAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PhpSpec\ObjectBehavior;

class GuzzleHttpAdapterSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $client->beConstructedWith([
            'headers' => [
                'Authorization' => 'Bearer my_access_token',
            ],
        ]);

        $this->beConstructedWith('my_access_token', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GuzzleHttpAdapter::class);
    }

    function it_returns_json_content(Client $client, Response $response, Stream $stream)
    {
        $client->get('http://sbin.dk')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_can_delete(Client $client, Response $response, Stream $stream)
    {
        $client->delete('http://sbin.dk/123')->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->delete('http://sbin.dk/123')->shouldBe('{"foo":"bar"}');
    }

    function it_can_put_basic(Client $client, Response $response, Stream $stream)
    {
        $client->put('http://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('foo');

        $this->put('http://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_array(Client $client, Response $response, Stream $stream)
    {
        $client->put('http://sbin.dk/456', [
            'json' => [
                'foo' => 'bar',
            ],
        ])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_can_post_basic(Client $client, Response $response, Stream $stream)
    {
        $client->post('http://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk/456')->shouldBe('{"foo":"bar"}');
    }

    function it_can_post_array(Client $client, Response $response, Stream $stream)
    {
        $client->post('http://sbin.dk/456', [
            'json' => [
                'foo' => 'bar',
            ],
        ])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_returns_last_response_header(Client $client, Response $response, Stream $stream)
    {
        $client->get('http://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');
        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
