<?php

declare(strict_types=1);

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;

class RegionSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Region');
    }


    function it_returns_an_array_of_region_entity(HttpClientInterface $httpClient)
    {
        $httpClient->get('https://api.digitalocean.com/v2/regions?per_page=200')->willReturn('{"regions": [{},{},{}]}');

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
