<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class RegionSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Region');
    }

    public function it_returns_an_array_of_region_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/regions?per_page='.PHP_INT_MAX)->willReturn('{"regions": [{},{},{}]}');

        $regions = $this->getAll();
        $regions->shouldBeArray();
        $regions->shouldHaveCount(3);
        $regions[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $regions[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $regions[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }
}
