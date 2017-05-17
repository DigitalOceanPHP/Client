<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class VolumeSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Volume');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/volumes?per_page=200')->willReturn('{"volumes": []}');

        $volumes = $this->getAll();
        $volumes->shouldBeArray();
        $volumes->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_volume_entity($adapter)
    {
        $total = 1;
        $response = <<<'EOT'
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

    public function it_returns_an_array_of_volume_entity_with_region($adapter)
    {
        $total = 1;
        $response = <<<'EOT'
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

    public function it_returns_an_array_of_volume_entity_with_region_and_name($adapter)
    {
        $total = 1;
        $response = <<<'EOT'
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

    public function it_returns_a_volume_entity_with_id($adapter)
    {
        $response = <<<'EOT'
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

    public function it_returns_the_created_volume_entity($adapter)
    {
        $response = <<<'EOT'
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

    public function it_throws_an_http_exception_if_not_possible_to_create_a_volume($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes',
                ['name' => 'example', 'description' => 'Block store for examples', 'size_gigabytes' => '10', 'region' => 'nyc1']
            )->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringCreate('example', 'Block store for examples', 10, 'nyc1');
    }

    public function it_deletes_the_volume_with_id_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1')
            ->shouldBeCalled();

        $this->delete('506f78a4-e098-11e5-ad9f-000f53306ae1');
    }

    public function it_throws_an_http_exception_when_trying_to_delete_with_id_inexisting_volume($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDelete('506f78a4-e098-11e5-ad9f-000f53306ae1');
    }

    public function it_deletes_the_volume_with_region_and_drivename_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes?name=example&region=ams1')
            ->shouldBeCalled();

        $this->deleteWithNameAndRegion('example', 'ams1');
    }

    public function it_throws_an_http_exception_when_trying_to_delete_with_region_and_drivename_inexisting_volume($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/volumes?name=example&region=ams1')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDeleteWithNameAndRegion('example', 'ams1');
    }

    public function it_returns_the_action_entity_after_attaching($adapter)
    {
        $response = <<<'EOT'
        {
            "action": {
                "id": 72531856,
                "status": "completed",
                "type": "attach_volume",
                "started_at": "2015-11-12T17:51:03Z",
                "completed_at": "2015-11-12T17:51:14Z",
                "resource_id": null,
                "resource_type": "volume",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                        "1gb",
                        "2gb",
                        "4gb",
                        "8gb",
                        "32gb",
                        "64gb",
                        "512mb",
                        "48gb",
                        "16gb"
                    ],
                    "features": [
                        "private_networking",
                        "backups",
                        "ipv6",
                        "metadata"
                    ],
                    "available": true
                },
                "region_slug": "nyc1"
            }
        }
EOT;
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/actions',
                ['type' => 'attach', 'droplet_id' => 123456, 'region' => 'nyc']
            )
            ->willReturn($response);

        $action = $this->attach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123456, 'nyc');
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_detaching($adapter)
    {
        $response = <<<'EOT'
        {
            "action": {
                "id": 68212773,
                "status": "in-progress",
                "type": "detach_volume",
                "started_at": "2015-10-15T17:46:15Z",
                "completed_at": null,
                "resource_id": null,
                "resource_type": "backend",
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
                "region_slug": "nyc1"
            }
        }
EOT;
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/actions',
                ['type' => 'detach', 'droplet_id' => 123456, 'region' => 'nyc']
            )
            ->willReturn($response);

        $action = $this->detach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123456, 'nyc');
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_resizing($adapter)
    {
        $response = <<<'EOT'
        {
            "action": {
                "id": 72531856,
                "status": "in-progress",
                "type": "resize",
                "started_at": "2015-11-12T17:51:03Z",
                "completed_at": "2015-11-12T17:51:14Z",
                "resource_id": null,
                "resource_type": "volume",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                        "1gb",
                        "2gb",
                        "4gb",
                        "8gb",
                        "32gb",
                        "64gb",
                        "512mb",
                        "48gb",
                        "16gb"
                    ],
                    "features": [
                        "private_networking",
                        "backups",
                        "ipv6",
                        "metadata"
                    ],
                    "available": true
                },
                "region_slug": "nyc1"
            }
        }
EOT;
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/actions',
                ['type' => 'resize', 'size_gigabytes' => 20, 'region' => 'nyc']
            )
            ->willReturn($response);

        $action = $this->resize('506f78a4-e098-11e5-ad9f-000f53306ae1', 20, 'nyc');
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_when_retrieving_action($adapter)
    {
        $response = <<<'EOT'
        {
            "action": {
                "id": 72531856,
                "status": "completed",
                "type": "attach_volume",
                "started_at": "2015-11-12T17:51:03Z",
                "completed_at": "2015-11-12T17:51:14Z",
                "resource_id": null,
                "resource_type": "volume",
                "region": {
                    "name": "New York 1",
                    "slug": "nyc1",
                    "sizes": [
                        "1gb",
                        "2gb",
                        "4gb",
                        "8gb",
                        "32gb",
                        "64gb",
                        "512mb",
                        "48gb",
                        "16gb"
                    ],
                    "features": [
                        "private_networking",
                        "backups",
                        "ipv6",
                        "metadata"
                    ],
                    "available": true
                },
                "region_slug": "nyc1"
            }
        }
EOT;
        $adapter
            ->get('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/actions/72531856')
            ->willReturn($response);

        $action = $this->getActionById('506f78a4-e098-11e5-ad9f-000f53306ae1', 72531856);
        $action->id->shouldEqual(72531856);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_an_array_of_action_entity($adapter)
    {
        $total = 1;

        $response = <<<'EOT'
        {
        "actions": [
            {
            "id": 72531856,
            "status": "completed",
            "type": "attach_volume",
            "started_at": "2015-11-21T21:51:09Z",
            "completed_at": "2015-11-21T21:51:09Z",
            "resource_id": null,
            "resource_type": "volume",
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
            "region_slug": "nyc1"
            }
        ],
        "links": {
        },
        "meta": {
            "total": 1
        }
    }
EOT;
        $adapter
            ->get('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/actions?per_page=200')
            ->willReturn($response);

        $actions = $this->getActions('506f78a4-e098-11e5-ad9f-000f53306ae1');
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        $actions[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $actions[0]->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');

        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_volumes_snapshots_which_are_snapshot_entity($adapter)
    {
        $total = 3;

        $response = <<<'EOT'
        {
            "snapshots": [
                {
                    "id": "ddcd0c62-3b45-11e7-b079-0242ac110606",
                    "name": "test_1",
                    "regions": [
                        "fra1"
                    ],
                    "created_at": "2017-05-17T21:14:51Z",
                    "resource_id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                    "resource_type": "volume",
                    "min_disk_size": 40,
                    "size_gigabytes": 25
                },
                {
                    "id": "dfaeb3f1-3b45-11e7-889c-0242ac110705",
                    "name": "test_2",
                    "regions": [
                        "fra1"
                    ],
                    "created_at": "2017-05-17T21:14:54Z",
                    "resource_id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                    "resource_type": "volume",
                    "min_disk_size": 40,
                    "size_gigabytes": 25
                },
                {
                    "id": "e1f9100f-3b45-11e7-b079-0242ac110606",
                    "name": "test_3",
                    "regions": [
                        "fra1"
                    ],
                    "created_at": "2017-05-17T21:14:58Z",
                    "resource_id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                    "resource_type": "volume",
                    "min_disk_size": 40,
                    "size_gigabytes": 25
                }
            ],
            "links": {},
            "meta": {
                "total": 3
            }
        }
EOT;

        $adapter
            ->get('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/snapshots?per_page=200')
            ->willReturn($response);

        $snapshots = $this->getSnapshots('506f78a4-e098-11e5-ad9f-000f53306ae1');
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount($total);
        foreach ($snapshots as $snapshot) {
            $snapshot->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Snapshot');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_snapshot_entity_after_snapshot_creation($adapter)
    {
        $response = <<<'EOT'
        {
            "snapshot": {
                "id": "902068ee-3b3f-11e7-93a1-0242ac116705",
                "name": "snapshot1-volume",
                "regions": [
                    "fra1"
                ],
                "created_at": "2017-05-17T20:29:44Z",
                "resource_id": "506f78a4-e098-11e5-ad9f-000f53306ae1",
                "resource_type": "volume",
                "min_disk_size": 40,
                "size_gigabytes": 25
            }
        }
EOT;
        $adapter
            ->post('https://api.digitalocean.com/v2/volumes/506f78a4-e098-11e5-ad9f-000f53306ae1/snapshots',
                ['name' => 'snapshot1-volume']
            )
            ->willReturn($response);

        $snapshot = $this->snapshot('506f78a4-e098-11e5-ad9f-000f53306ae1', 'snapshot1-volume');
        $snapshot->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Snapshot');
        $snapshot->id->shouldBe('902068ee-3b3f-11e7-93a1-0242ac116705');
        $snapshot->name->shouldBe('snapshot1-volume');
    }
}
