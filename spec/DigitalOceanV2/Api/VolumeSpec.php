<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

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

        $volumes = $this->getAll("nyc1");
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount($total);
                
        $volumes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volumes[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $volumes[0]->region->slug->shouldBeEqualTo("nyc1");
        
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

        $volumes = $this->getByNameAndRegion("example", "nyc1");
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount($total);
                
        $volumes[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Volume');
        $volumes[0]->name->shouldBeEqualTo("example");
        $volumes[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $volumes[0]->region->slug->shouldBeEqualTo("nyc1");
        
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }
}
