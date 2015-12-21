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
        $total = 3;
        $adapter->get('https://api.digitalocean.com/v2/sizes?per_page=200')
            ->willReturn(sprintf('{"sizes": [{},{},{}], "meta": {"total": %d}}', $total));

        $sizes = $this->getAll();
        $sizes->shouldBeArray();
        $sizes->shouldHaveCount($total);
        foreach ($sizes as $size) {
            $size->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Size');
        }

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }
}
