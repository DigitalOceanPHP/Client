<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class ImageSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Image');
    }

    function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/images')->willReturn('{"images": []}');

        $images = $this->getAll();
        $images->shouldBeArray();
        $images->shouldHaveCount(0);
    }

    function it_returns_an_array_of_image_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/images')->willReturn('{"images": [{},{},{}]}');

        $images = $this->getAll();
        $images->shouldBeArray();
        $images->shouldHaveCount(3);
        $images[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $images[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $images[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_returns_an_image_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/images/123')
            ->willReturn('
                {
                    "image": {
                        "id": 123,
                        "name": "Ubuntu 13.04",
                        "distribution": null,
                        "slug": null,
                        "public": false,
                        "regions": [
                          "nyc1"
                        ],
                        "created_at": "2014-06-27T21:10:28Z"
                    }
                }
            ')
        ;

        $this->getById(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_returns_an_image_entity_get_by_its_slug($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/images/foo-bar')
            ->willReturn('
                {
                    "image": {
                        "id": 456,
                        "name": "Ubuntu 13.04",
                        "distribution": null,
                        "slug": "foo-bar",
                        "public": false,
                        "regions": [
                          "nyc1"
                        ],
                        "created_at": "2014-06-27T21:10:28Z"
                    }
                }
            ')
        ;

        $this->getBySlug('foo-bar')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_returns_the_updated_image($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/images/123',
                array('Content-Type: application/json'),
                '{"name":"bar-baz"}'
            )
            ->willReturn('
                {
                    "image": {
                        "id": 123,
                        "name": "bar-baz",
                        "distribution": null,
                        "slug": null,
                        "public": false,
                        "regions": [
                          "nyc1"
                        ],
                        "created_at": "2014-06-27T21:10:28Z"
                    }
                }
            ')
        ;

        $this->update(123, 'bar-baz')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_throws_a_runtime_exception_when_trying_to_update_an_inexisting_image($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/images/0',
                array('Content-Type: application/json'),
                '{"name":"baz-baz"}'
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('update', array(0, 'baz-baz'));
    }

    function it_deletes_the_image_and_returns_nothing($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/images/678',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willReturn('foo')
        ;

        $this->delete(678);
    }

    function it_throws_a_runtime_exception_when_trying_to_delete_an_inexisting_image($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/images/0',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('delete', array(0));
    }

    function it_transfer_the_image_to_an_other_region_and_returns_its_image($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/images/123/actions',
                array('Content-Type: application/json'),
                '{"type":"transfer","region":"nyc2"}'
            )
            ->willReturn('
                {
                    "action": {
                        "id": 22,
                        "status": "in-progress",
                        "type": "transfer",
                        "started_at": "2014-06-27T21:10:27Z",
                        "completed_at": null,
                        "resource_id": 449676390,
                        "resource_type": "image",
                        "region": "nyc2"
                    }
                }
            ')
        ;

        $this->transfer(123, 'nyc2')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    function it_throws_an_runtime_exception_if_trying_to_transfer_to_unknown_region_slug($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/images/0/actions',
                array('Content-Type: application/json'),
                '{"type":"transfer","region":"foo"}'
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('transfer', array(0, 'foo'));
    }

    function it_returns_the_requested_action_entity_of_the_given_image($adapter)
    {
        $adapter
            ->get(
                'https://api.digitalocean.com/v2/images/123/actions/456'
            )
            ->willReturn('
                {
                    "action": {
                        "id": 22,
                        "status": "in-progress",
                        "type": "transfer",
                        "started_at": "2014-06-27T21:10:27Z",
                        "completed_at": null,
                        "resource_id": 449676390,
                        "resource_type": "image",
                        "region": "nyc2"
                    }
                }
            ')
        ;

        $this->getAction(123, 456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    function it_throws_an_runtime_exception_when_retreiving_non_existing_image_action($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/images/0/actions/0')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('getAction', array(0, 0));
    }
}
