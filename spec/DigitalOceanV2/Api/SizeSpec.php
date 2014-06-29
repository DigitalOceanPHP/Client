<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class SizeSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Size');
    }

    function it_returns_an_array_of_size_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/sizes')->willReturn('{"sizes": [{},{},{}]}');

        $sizes = $this->getAll();
        $sizes->shouldBeArray();
        $sizes->shouldHaveCount(3);
        $sizes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Size');
        $sizes[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Size');
        $sizes[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Size');
    }
}
