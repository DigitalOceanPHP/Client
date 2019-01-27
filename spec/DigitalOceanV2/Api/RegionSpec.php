<?php

namespace spec\DigitalOceanV2\Api;

class RegionSpec extends \PhpSpec\ObjectBehavior
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
        $this->shouldHaveType('DigitalOceanV2\Api\Region');
    }

    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_an_array_of_region_entity($adapter)
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
