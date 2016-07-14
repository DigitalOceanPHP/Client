<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class VolumeSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Volume');
    }

    function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/volumes?per_page=200')->willReturn('{"volumes": []}');

        $volumes = $this->getAll();
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount(0);
    }

    function it_returns_an_array_of_volume_entity($adapter)
    {
        $total = 1;
        $response = <<<EOT
            {"volumes": [
                {
                "id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                    "512mb",
                    "1gb",
                    "2gb",
                    "4gb",
                    "8gb",
                    "16gb",
                    "32gb",
                    "48gb",
                    "64gb"
                    ],
                    "features": [
                    "private_networking",
                    "backups",
                    "ipv6",
                    "metadata"
                    ],
                    "available": true
                },
                "droplet_ids": [

                ],
                "name": "example",
                "description": "Block store for examples",
                "size_gigabytes": 10,
                "created_at": "2016-03-02T17:00:49Z"
                }
            ],
            "links": {
            },
            "meta": {
                "total": 1
            }
        }        
EOT;

        $adapter->get('https://api.digitalocean.com/v2/volumes?per_page=200')
            ->willReturn($response);

        $volumes = $this->getAll();
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount($total);

        $volumes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volumes[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    function it_returns_an_array_of_volume_entity_with_region($adapter)
    {
        $total = 1;
        $response = <<<EOT
            {"volumes": [
                {
                "id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                    "512mb",
                    "1gb",
                    "2gb",
                    "4gb",
                    "8gb",
                    "16gb",
                    "32gb",
                    "48gb",
                    "64gb"
                    ],
                    "features": [
                    "private_networking",
                    "backups",
                    "ipv6",
                    "metadata"
                    ],
                    "available": true
                },
                "droplet_ids": [

                ],
                "name": "example",
                "description": "Block store for examples",
                "size_gigabytes": 10,
                "created_at": "2016-03-02T17:00:49Z"
                }
            ],
            "links": {
            },
            "meta": {
                "total": 1
            }
        }        
EOT;

        $adapter->get('https://api.digitalocean.com/v2/volumes?per_page=200&region=nyc1')
            ->willReturn($response);

        $volumes = $this->getAll('nyc1');
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount($total);

        $volumes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volumes[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $volumes[0]->region->slug->shouldBeEqualTo('nyc1');

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    function it_returns_an_array_of_volume_entity_with_region_and_name($adapter)
    {
        $total = 1;
        $response = <<<EOT
            {"volumes": [
                {
                "id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                    "512mb",
                    "1gb",
                    "2gb",
                    "4gb",
                    "8gb",
                    "16gb",
                    "32gb",
                    "48gb",
                    "64gb"
                    ],
                    "features": [
                    "private_networking",
                    "backups",
                    "ipv6",
                    "metadata"
                    ],
                    "available": true
                },
                "droplet_ids": [

                ],
                "name": "example",
                "description": "Block store for examples",
                "size_gigabytes": 10,
                "created_at": "2016-03-02T17:00:49Z"
                }
            ],
            "links": {
            },
            "meta": {
                "total": 1
            }
        }        
EOT;

        $adapter->get('https://api.digitalocean.com/v2/volumes?per_page=200&region=nyc1&name=example')
            ->willReturn($response);

        $volumes = $this->getByNameAndRegion('example', 'nyc1');
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount($total);

        $volumes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volumes[0]->name->shouldBeEqualTo('example');
        $volumes[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $volumes[0]->region->slug->shouldBeEqualTo('nyc1');

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    function it_returns_a_volume_entity_with_id($adapter)
    {
        $response = <<<EOT
            {
                "volume": {
                    "id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                    "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                        "512mb",
                        "1gb",
                        "2gb",
                        "4gb",
                        "8gb",
                        "16gb",
                        "32gb",
                        "48gb",
                        "64gb"
                    ],
                    "features": [
                        "private_networking",
                        "backups",
                        "ipv6",
                        "metadata"
                    ],
                    "available": true
                    },
                    "droplet_ids": [

                    ],
                    "name": "example",
                    "description": "Block store for examples",
                    "size_gigabytes": 10,
                    "created_at": "2016-03-02T17:00:49Z"
                }
            }       
EOT;

        $adapter->get('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1?per_page=200')
            ->willReturn($response);

        $volume = $this->getById('506f78a4-e098-11e5-ad9f-000f53306ae1');

        $volume->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volume->id->shouldBeEqualTo('506f78a4-e098-11e5-ad9f-000f53306ae1');
        $volume->name->shouldBeEqualTo('example');
        $volume->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $volume->region->slug->shouldBeEqualTo('nyc1');
    }

    function it_returns_the_created_volume_entity($adapter)
    {
        $response = <<<EOT
            {
                "volume": {
                    "id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                    "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                        "512mb",
                        "1gb",
                        "2gb",
                        "4gb",
                        "8gb",
                        "16gb",
                        "32gb",
                        "48gb",
                        "64gb"
                    ],
                    "features": [
                        "private_networking",
                        "backups",
                        "ipv6",
                        "metadata"
                    ],
                    "available": true
                    },
                    "droplet_ids": [

                    ],
                    "name": "example",
                    "description": "Block store for examples",
                    "size_gigabytes": 10,
                    "created_at": "2016-03-02T17:00:49Z"
                }
            }       
EOT;

        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes',
                ['name' => 'example', 'description' => 'Block store for examples', 'size_gigabytes' => '10', 'region' => 'nyc1']
            )
            ->willReturn($response);

        $volume = $this->create('example', 'Block store for examples', 10, 'nyc1');

        $volume->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volume->id->shouldBeEqualTo('506f78a4-e098-11e5-ad9f-000f53306ae1');
        $volume->name->shouldBeEqualTo('example');
        $volume->description->shouldBeEqualTo('Block store for examples');
        $volume->description->shouldBeEqualTo('Block store for examples');
        $volume->sizeGigabytes->shouldBeEqualTo(10);
        $volume->region->slug->shouldBeEqualTo('nyc1');
    }

    function it_throws_an_http_exception_if_not_possible_to_create_a_volume($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes',
                ['name' => 'example', 'description' => 'Block store for examples', 'size_gigabytes' => '10', 'region' => 'nyc1']
            )->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringCreate('example', 'Block store for examples', 10, 'nyc1');
    }

    function it_deletes_the_volume_with_id_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1')
            ->shouldBeCalled();

        $this->delete('506f78a4-e098-11e5-ad9f-000f53306ae1');
    }

    function it_throws_an_http_exception_when_trying_to_delete_with_id_inexisting_volume($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDelete('506f78a4-e098-11e5-ad9f-000f53306ae1');
    }

    function it_deletes_the_volume_with_region_and_drivename_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes?name=example&region=ams1')
            ->shouldBeCalled();

        $this->deleteWithNameAndRegion('example', 'ams1');
    }

    function it_throws_an_http_exception_when_trying_to_delete_with_region_and_drivename_inexisting_volume($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes?name=example&region=ams1')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDeleteWithNameAndRegion('example', 'ams1');
    }
}
