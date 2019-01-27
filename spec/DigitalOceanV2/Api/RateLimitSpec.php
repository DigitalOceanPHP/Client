<?php

namespace spec\DigitalOceanV2\Api;

class RateLimitSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function let($adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\RateLimit');
    }

    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_null_if_there_is_no_previous_request($adapter)
    {
        $adapter->getLatestResponseHeaders()->willReturn(null);

        $this->getRateLimit()->shouldBeNull();
    }

    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_rate_limit_entity($adapter)
    {
        $adapter->getLatestResponseHeaders()->willReturn([
            'limit' => 1200,
            'remaining' => 1000,
            'reset' => time(),
        ]);

        $this->getRateLimit()->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\RateLimit');
    }
}
