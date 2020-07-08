<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class RegionSpec extends \PhpSpec\ObjectBehavior
{

    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Region');
    }


    function it_returns_an_array_of_region_entity(AdapterInterface $adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/regions?per_page=200')->willReturn('{"regions": [{},{},{}]}');

        $regions = $this->getAll();
        $regions->shouldBeArray();
        $regions->shouldHaveCount(3);

        foreach ($regions as $region) {
            /**
             * @var \DigitalOceanV2\Entity\Region|\PhpSpec\Wrapper\Subject $region
             */
            $region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        }
    }
}
