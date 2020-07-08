<?php

namespace spec\DigitalOceanV2\Adapter;

class GuzzleHttpAdapterSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \GuzzleHttp\Client $client
     */
    function let($client)
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
        $this->shouldHaveType('DigitalOceanV2\Adapter\GuzzleHttpAdapter');
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_returns_json_content($client, $response, $stream)
    {
        $client->request('GET', 'https://sbin.dk')->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->get('https://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_can_delete($client, $response, $stream)
    {
        $client->request('DELETE', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('foo');

        $this->delete('https://sbin.dk/456')->shouldBe('foo');
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_can_put_basic($client, $response, $stream)
    {
        $client->request('PUT', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('foo');

        $this->put('https://sbin.dk/456')->shouldBe('foo');
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_can_put_array($client, $response, $stream)
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

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_can_post_basic($client, $response, $stream)
    {
        $client->request('POST', 'https://sbin.dk/456', ['body' => ''])->willReturn($response);

        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('{"foo":"bar"}');

        $this->post('https://sbin.dk/456')->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_can_post_array($client, $response, $stream)
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

    /**
     * @param \GuzzleHttp\Client $client
     * @param \GuzzleHttp\Psr7\Response $response
     * @param \GuzzleHttp\Psr7\Stream $stream
     */
    function it_returns_last_response_header($client, $response, $stream)
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
