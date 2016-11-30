<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class SnapshotSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Snapshot');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/snapshots?per_page=200')->willReturn('{"snapshots": []}');

        $snapshots = $this->getAll();
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_snapshot_entity($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/snapshots?per_page=200')
            ->willReturn('{
                "snapshots": [
                    {
                        "id": "4f60fc64-85d1-11e6-a004-000f53315871",
                        "name": "snapshot1-volume",
                        "regions": [
                            "nyc1"
                        ],
                        "created_at": "2016-09-28T23:14:30Z",
                        "resource_id": "89bcc42f-85cf-11e6-a004-000f53315871",
                        "resource_type": "volume",
                        "min_disk_size": 10,
                        "size_gigabytes": 0
                    },
                    {
                        "id": "4f60fc64-11e6-85d1-a004-000f53315871",
                        "name": "snapshot1-droplet",
                        "regions": [
                            "nyc1"
                        ],
                        "created_at": "2016-09-28T23:14:30Z",
                        "resource_id": "89bcc42f-85cf-11e6-a004-000f53315871",
                        "resource_type": "droplet",
                        "min_disk_size": 10,
                        "size_gigabytes": 0
                    }
                ],
                "links": {
                    "pages": {
                        "last": "https://api.digitalocean.com/v2/snapshots?page=1&per_page=1&resource_type=volume",
                        "next": "https://api.digitalocean.com/v2/snapshots?page=1&per_page=1&resource_type=volume"
                    }
                },
                "meta": {
                    "total": 2
                }
                }');

        $snapshots = $this->getAll();
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount(2);
        foreach ($snapshots as $snapshot) {
            $snapshot->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Snapshot');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe(2);
    }

    public function it_returns_an_array_of_snapshot_entity_by_type($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/snapshots?per_page=200&resource_type=volume')
            ->willReturn('{
                "snapshots": [
                    {
                        "id": "4f60fc64-85d1-11e6-a004-000f53315871",
                        "name": "snapshot1-volume",
                        "regions": [
                            "nyc1"
                        ],
                        "created_at": "2016-09-28T23:14:30Z",
                        "resource_id": "89bcc42f-85cf-11e6-a004-000f53315871",
                        "resource_type": "volume",
                        "min_disk_size": 10,
                        "size_gigabytes": 0
                    }
                ],
                "links": {
                    "pages": {
                        "last": "https://api.digitalocean.com/v2/snapshots?page=1&per_page=1&resource_type=volume",
                        "next": "https://api.digitalocean.com/v2/snapshots?page=1&per_page=1&resource_type=volume"
                    }
                },
                "meta": {
                    "total": 1
                }
                }');

        $criteria['type'] = 'volume';
        $snapshots = $this->getAll($criteria);
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount(1);
        foreach ($snapshots as $snapshot) {
            $snapshot->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Snapshot');
        }
        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe(1);
    }

    public function it_returns_snapshot_entity_by_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/snapshots/4f60fc64-85d1-11e6-a004-000f53315871')
            ->willReturn('{
                "snapshot":
                    {
                        "id": "4f60fc64-85d1-11e6-a004-000f53315871",
                        "name": "snapshot1-volume",
                        "regions": [
                            "nyc1"
                        ],
                        "created_at": "2016-09-28T23:14:30Z",
                        "resource_id": "89bcc42f-85cf-11e6-a004-000f53315871",
                        "resource_type": "volume",
                        "min_disk_size": 10,
                        "size_gigabytes": 0
                    }
                }');

        $snapshot = $this->getById('4f60fc64-85d1-11e6-a004-000f53315871');
        $snapshot->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Snapshot');
        $snapshot->id->shouldBe('4f60fc64-85d1-11e6-a004-000f53315871');
        $snapshot->name->shouldBe('snapshot1-volume');
    }

    public function it_deletes_the_snapshot_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/snapshots/4f60fc64-85d1-11e6-a004-000f53315871')
            ->shouldBeCalled();

        $this->delete('4f60fc64-85d1-11e6-a004-000f53315871');
    }
}
