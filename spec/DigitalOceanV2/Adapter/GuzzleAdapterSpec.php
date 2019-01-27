<?php

namespace spec\DigitalOceanV2\Adapter;

class GuzzleAdapterSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \Guzzle\Http\Client $client
     */
    function let($client)
    {
        $client->setDefaultOption('headers/Authorization', 'Bearer my_access_token')->willReturn($client);

        $this->beConstructedWith('my_access_token', $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\GuzzleAdapter');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\Request $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_returns_json_content($client, $request, $response)
    {
        $client->get('http://sbin.dk')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_can_delete($client, $request, $response)
    {
        $client->delete('http://sbin.dk/123')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->delete('http://sbin.dk/123');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_can_put_basic($client, $request, $response)
    {
        $client->put('http://sbin.dk/456')->willReturn($request);
        $request->setBody('')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('foo');

        $this->put('http://sbin.dk/456')->shouldBe('foo');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_can_put_array($client, $request, $response)
    {
        $client->put('http://sbin.dk/456')->willReturn($request);
        $request->setBody('{"foo":"bar"}', 'application/json')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_can_post_basic($client, $request, $response)
    {
        $client->post('http://sbin.dk/456')->willReturn($request);
        $request->setBody('')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk/456')->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_can_post_array($client, $request, $response)
    {
        $client->post('http://sbin.dk/456')->willReturn($request);
        $request->setBody('{"foo":"bar"}', 'application/json')->willReturn($request);
        $request->send()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getBody(true)->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk/456', ['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    /**
     * @param \Guzzle\Http\Client $client
     * @param \Guzzle\Http\Message\EntityEnclosingRequest $request
     * @param \Guzzle\Http\Message\Response $response
     */
    function it_returns_last_response_header($client, $request, $response)
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
