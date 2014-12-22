<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class RateLimitSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\RateLimit');
    }

    public function it_retuns_null_if_there_is_no_previous_request($adapter)
    {
        $adapter->getLatestResponseHeaders()->willReturn(null);

        $this->getRateLimit()->shouldBeNull();
    }

    public function it_returns_rate_limit_entity($adapter)
    {
        $adapter->getLatestResponseHeaders()->willReturn(array(
            'limit'     => 1200,
            'remaining' => 1000,
            'reset'     => time(),
        ));

        $this->getRateLimit()->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\RateLimit');
    }
}
