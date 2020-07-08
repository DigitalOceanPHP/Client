<?php

namespace spec\DigitalOceanV2\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

class GuzzleHttpClientSpec extends \PhpSpec\ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\HttpClient\GuzzleHttpClient');
    }

    function it_returns_json_content(Client $client, Response $response, Stream $stream)
    {
        $client->request('GET', 'https://sbin.dk')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->get('https://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_can_delete(Client $client, Response $response, Stream $stream)
    {
        $client->request('DELETE', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('foo');

        $this->delete('https://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_basic(Client $client, Response $response, Stream $stream)
    {
        $client->request('PUT', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('foo');

        $this->put('https://sbin.dk/456')->shouldBe('foo');
    }

    function it_can_put_array(Client $client, Response $response, Stream $stream)
    {
        $client->request('PUT', 'https://sbin.dk/456', [
            'json' => [
                'foo' => 'bar',
            ],
        ])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->put('https://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_can_post_basic(Client $client, Response $response, Stream $stream)
    {
        $client->request('POST', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->post('https://sbin.dk/456')->shouldBe('{"foo":"bar"}');
    }

    function it_can_post_array(Client $client, Response $response, Stream $stream)
    {
        $client->request('POST', 'https://sbin.dk/456', [
            'json' => [
                'foo' => 'bar',
            ],
        ])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->post('https://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_returns_last_response_header(Client $client, Response $response, Stream $stream)
    {
        $client->request('GET', 'https://sbin.dk')->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');
        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->get('https://sbin.dk')->shouldBe('{"foo":"bar"}');
        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
