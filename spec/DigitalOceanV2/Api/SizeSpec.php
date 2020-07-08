<?php

declare(strict_types=1);

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;

class SizeSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Size');
    }


    function it_returns_an_array_of_size_entity(HttpClientInterface $httpClient)
    {
        $total = 3;
        $httpClient->get('https://api.digitalocean.com/v2/sizes?per_page=200')
            ->willReturn(sprintf('{"sizes": [{},{},{}], "meta": {"total": %d}}', $total));

        $sizes = $this->getAll();
        $sizes->shouldBeArray();
        $sizes->shouldHaveCount($total);
        foreach ($sizes as $size) {
            /**
             * @var \DigitalOceanV2\Entity\Size|\PhpSpec\Wrapper\Subject $size
             */
            $size->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Size');
        }

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }
}
