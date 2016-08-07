<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class ImageSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Image');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/images?per_page=200')->willReturn('{"images": []}');

        $images = $this->getAll();
        $images->shouldBeArray();
        $images->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/images?per_page=200')
            ->willReturn(sprintf('{"images": [{},{},{}], "meta": {"total": %d}}', $total));

        $images = $this->getAll();
        $images->shouldBeArray();
        $images->shouldHaveCount($total);
        foreach ($images as $image) {
            $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_distribution_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/images?per_page=200'.'&type=distribution')
            ->willReturn(sprintf('{"images": [{},{},{}], "meta": {"total": %d}}', $total));

        $images = $this->getAll(['type' => 'distribution']);
        $images->shouldBeArray();
        $images->shouldHaveCount($total);
        foreach ($images as $image) {
            $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_application_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/images?per_page=200'.'&type=application')
            ->willReturn(sprintf('{"images": [{},{},{}], "meta": {"total": %d}}', $total));

        $images = $this->getAll(['type' => 'application']);
        $images->shouldBeArray();
        $images->shouldHaveCount($total);
        foreach ($images as $image) {
            $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_private_application_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/images?per_page=200'.'&type=application&private=true')
            ->willReturn(sprintf('{"images": [{},{},{}], "meta": {"total": %d}}', $total));

        $images = $this->getAll(['type' => 'application', 'private' => true]);
        $images->shouldBeArray();
        $images->shouldHaveCount($total);
        foreach ($images as $image) {
            $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_private_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/images?per_page=200'.'&private=true')
            ->willReturn(sprintf('{"images": [{},{},{}], "meta": {"total": %d}}', $total));

        $images = $this->getAll(['private' => true]);
        $images->shouldBeArray();
        $images->shouldHaveCount($total);
        foreach ($images as $image) {
            $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_image_entity_get_by_its_id($adapter)
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
                        "created_at": "2014-06-27T21:10:28Z",
                        "min_disk_size": 20,
                        "size_gigabytes": 2.34
                      }
                }
            ');

        $image = $this->getById(123);
        $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $image->sizeGigabytes->shouldBe(2.34);
        $this->getMeta()->shouldBeNull();
    }

    public function it_returns_an_image_entity_get_by_its_slug($adapter)
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
                        "created_at": "2014-06-27T21:10:28Z",
                        "min_disk_size": 20
                    }
                }
            ');

        $this->getBySlug('foo-bar')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    public function it_returns_the_updated_image($adapter)
    {
        $adapter
            ->put('https://api.digitalocean.com/v2/images/123', ['name' => 'bar-baz'])
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
                        "created_at": "2014-06-27T21:10:28Z",
                        "min_disk_size": 20
                    }
                }
            ');

        $this->update(123, 'bar-baz')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    public function it_throws_an_http_exception_when_trying_to_update_an_inexisting_image($adapter)
    {
        $adapter
            ->put('https://api.digitalocean.com/v2/images/0', ['name' => 'baz-baz'])
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('update', [0, 'baz-baz']);
    }

    public function it_deletes_the_image_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/images/678')
            ->shouldBeCalled();

        $this->delete(678);
    }

    public function it_throws_an_http_exception_when_trying_to_delete_an_inexisting_image($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/images/0')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('delete', [0]);
    }

    public function it_transfer_the_image_to_an_other_region_and_returns_its_image($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/images/123/actions', ['type' => 'transfer', 'region' => 'nyc2'])
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
                        "region": {
                            "name": "New York 3",
                            "slug": "nyc3",
                            "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                            "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                            "available": true
                        },
                        "region_slug": "nyc2"
                    }
                }
            ');

        $image = $this->transfer(123, 'nyc2');
        $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $image->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_throws_an_http_exception_if_trying_to_transfer_to_unknown_region_slug($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/images/0/actions', ['type' => 'transfer', 'region' => 'foo'])
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('transfer', [0, 'foo']);
    }

    public function it_can_convert_the_image_to_a_snapshot($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/images/123/actions', ['type' => 'convert'])
            ->willReturn('
                {
                    "action": {
                        "id": 22,
                        "status": "completed",
                        "type": "convert_to_snapshot",
                        "started_at": "2015-03-24T19:02:47Z",
                        "completed_at": "2015-03-24T19:02:47Z",
                        "resource_id": 449676390,
                        "resource_type": "image",
                        "region": null,
                        "region_slug": null
                    }
                }
            ');

        $image = $this->convert(123);
        $image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $image->region->shouldReturn(null);
    }

    public function it_returns_the_requested_action_entity_of_the_given_image($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/images/123/actions/456')
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
                        "region": {
                            "name": "New York 3",
                            "slug": "nyc3",
                            "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                            "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                            "available": true
                        },
                        "region_slug": "nyc2"
                    }
                }
            ');

        $action = $this->getAction(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_throws_an_http_exception_when_retrieving_non_existing_image_action($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/images/0/actions/0')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('getAction', [0, 0]);
    }
}
