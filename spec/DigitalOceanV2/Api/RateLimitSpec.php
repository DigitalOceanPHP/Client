<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;

class RateLimitSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\RateLimit');
    }


    function it_returns_null_if_there_is_no_previous_request(HttpClientInterface $httpClient)
    {
        $httpClient->getLatestResponseHeaders()->willReturn(null);

        $this->getRateLimit()->shouldBeNull();
    }


    function it_returns_rate_limit_entity(HttpClientInterface $httpClient)
    {
        $httpClient->getLatestResponseHeaders()->willReturn([
            'limit' => 1200,
            'remaining' => 1000,
            'reset' => time(),
        ]);

        $this->getRateLimit()->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\RateLimit');
    }
}
