<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class DropletSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Droplet');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets?per_page=200&page=1')->willReturn('{"droplets": []}');

        $droplets = $this->getAll();
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_droplet_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets?per_page=200&page=1')
            ->willReturn(sprintf('{"droplets": [{},{},{}], "meta": {"total": %d}}', $total));

        $droplets = $this->getAll();
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount($total);
        foreach ($droplets as $droplet) {
            $droplet->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_droplet_neighbors_for_a_given_droplet_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/neighbors')
            ->willReturn('{"droplets" : [{},{},{}]}');

        $droplets = $this->getNeighborsById(123);
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount(3);
        foreach ($droplets as $droplet) {
            $droplet->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        }
    }

    public function it_returns_an_array_of_upgrade_entity($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplet_upgrades')
            ->willReturn('[{}, {}, {}]');

        $upgrades = $this->getUpgrades();
        $upgrades->shouldBeArray();
        $upgrades->shouldHaveCount(3);
        foreach ($upgrades as $upgrade) {
            $upgrade->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Upgrade');
        }
    }

    public function it_returns_an_array_of_droplet_that_are_running_on_the_same_physical_hardware($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/reports/droplet_neighbors')
            ->willReturn('{"neighbors" : [{},{},{}]}');

        $neighbors = $this->getAllNeighbors(123);
        $neighbors->shouldBeArray();
        $neighbors->shouldHaveCount(3);
        foreach ($neighbors as $neighbor) {
            $neighbor->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        }
    }

    public function it_returns_a_droplet_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/14')
            ->willReturn('
                {
                    "droplet": {
                        "id": 14,
                        "name": "test.example.com",
                        "region": {
                            "slug": "nyc2",
                            "name": "New York",
                            "sizes": [
                                "512mb",
                                "1gb"
                            ],
                            "available": true
                        },
                        "image": {
                            "id": 119192817,
                            "name": "Ubuntu 13.04",
                            "distribution": "ubuntu",
                            "slug": "ubuntu1304",
                            "public": true,
                            "regions": [
                                "nyc1"
                            ]
                        },
                        "kernel": {
                            "id": 1001,
                            "name": "Ubuntu 14.04 x64 vmlinuz-3.13.0-24-generic (1221)",
                            "version": "3.13.0-24-generic"
                        },
                        "size_slug": "512mb",
                        "locked": false,
                        "created_at": "2014-07-02T15:22:06Z",
                        "features": [
                            "virtio",
                            "private_networking",
                            "backups",
                            "ipv6"
                        ],
                        "status": "active",
                        "tags" : [
                            "tag-1" ,
                            "tag-2"
                        ],
                        "networks": {
                            "v4": [
                                {
                                    "ip_address": "127.0.0.1",
                                    "netmask": "255.255.255.0",
                                    "gateway": "127.0.0.2",
                                    "type": "public"
                                }
                            ],
                            "v6": [
                                {
                                    "ip_address": "2400:6180:0000:00D0:0000:0000:0009:7001",
                                    "netmask": 64,
                                    "gateway": "2400:6180:0000:00D0:0000:0000:0009:7000",
                                    "type": "public"
                                }
                            ]
                        },
                        "backup_ids": [
                            119192840
                        ],
                        "snapshot_ids": [
                            119192841
                        ],
                        "volume_ids": [
                            "123321123",
                            "789987789"
                        ],
                        "next_backup_window": {
                            "start": "2015-02-16T19:00:00Z",
                            "end": "2015-02-17T18:00:00Z"
                        }
                    }
                }
            ');

        $droplet = $this->getById(14);
        $droplet->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        $droplet->tags->shouldBeArray();
        $droplet->tags->shouldHaveCount(2);
        $droplet->networks->shouldBeArray();
        $droplet->networks->shouldHaveCount(2);
        $droplet->networks[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Network');
        $droplet->networks[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Network');
        $droplet->kernel->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
        $droplet->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $droplet->image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $droplet->volumeIds[0]->shouldBe('123321123');
        $droplet->volumeIds[1]->shouldBe('789987789');
        $this->getMeta()->shouldBeNull();
        $droplet->sizeSlug->shouldBe('512mb');
        $droplet->backupsEnabled->shouldBe(true);
        $droplet->privateNetworkingEnabled->shouldBe(true);
        $droplet->ipv6Enabled->shouldBe(true);
        $droplet->virtIOEnabled->shouldBe(true);
        $droplet->nextBackupWindow->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\NextBackupWindow');
    }

    public function it_returns_a_droplet_entity_even_if_backup_is_disabled($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/1234')
            ->willReturn('
                {
                    "droplet": {
                        "id": 14,
                        "name": "test.example.com",
                        "region": {},
                        "image": {},
                        "kernel": {},
                        "size_slug": "512mb",
                        "locked": false,
                        "created_at": "",
                        "features": ["virtio", "private_networking", "backups", "ipv6"],
                        "status": "active",
                        "tags" : [],
                        "networks": {
                            "v4": [
                                {
                                    "ip_address": "127.0.0.1",
                                    "netmask": "255.255.255.0",
                                    "gateway": "127.0.0.2",
                                    "type": "public"
                                }
                            ],
                            "v6": [
                                {
                                    "ip_address": "2400:6180:0000:00D0:0000:0000:0009:7001",
                                    "netmask": 64,
                                    "gateway": "2400:6180:0000:00D0:0000:0000:0009:7000",
                                    "type": "public"
                                }
                            ]
                        },
                        "backup_ids": [],
                        "snapshot_ids": [],
                        "next_backup_window": null
                    }
                }
            ');

        $droplet = $this->getById(1234);
        $droplet->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        $droplet->tags->shouldBeArray();
        $droplet->tags->shouldHaveCount(0);
        $droplet->networks->shouldBeArray();
        $droplet->networks->shouldHaveCount(2);
        $droplet->networks[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Network');
        $droplet->networks[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Network');
        $droplet->kernel->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
        $droplet->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $droplet->image->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $this->getMeta()->shouldBeNull();
        $droplet->sizeSlug->shouldBe('512mb');
        $droplet->backupsEnabled->shouldBe(true);
        $droplet->privateNetworkingEnabled->shouldBe(true);
        $droplet->ipv6Enabled->shouldBe(true);
        $droplet->virtIOEnabled->shouldBe(true);
        $droplet->nextBackupWindow->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\NextBackupWindow');
    }

    public function it_throws_an_http_exception_if_requested_droplet_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/1234567')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringGetById(1234567);
    }

    public function it_returns_the_created_droplet_entity_without_ssh_keys($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                ['name' => 'foo', 'region' => 'nyc1', 'size' => '512mb', 'image' => 123456, 'backups' => 'false', 'ipv6' => 'false', 'private_networking' => 'false', 'monitoring' => 'true', 'volumes' => ['123', '456'], 'tags' => ['foo', 'bar']]
            )
            ->willReturn('{"droplet": {}}');

        $this->create('foo', 'nyc1', '512mb', 123456, false, false, false, [], '', true, ['123', '456'], ['foo', 'bar'])->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
    }

    public function it_returns_the_created_droplet_entity_with_ssh_keys($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                ['name' => 'bar', 'region' => 'nyc2', 'size' => '512mb', 'image' => 'ubuntu', 'backups' => 'true', 'ipv6' => 'true', 'private_networking' => 'true', 'ssh_keys' => ['123', '456', '789'], 'monitoring' => 'true', 'volumes' => ['123', '456'], 'tags' => ['foo', 'bar']]
            )
            ->willReturn('{"droplet":{}}');

        $this
            ->create('bar', 'nyc2', '512mb', 'ubuntu', true, true, true, ['123', '456', '789'], '', true, ['123', '456'], ['foo', 'bar'])
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
    }

    public function can_create_multiple_droplets_at_the_same_time($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                ['names' => ['foo', 'bar'], 'region' => 'nyc1', 'size' => '512mb', 'image' => 123456, 'backups' => 'false', 'ipv6' => 'false', 'private_networking' => 'false', 'ssh_keys' => ['123', '456', '789'], 'monitoring' => 'true', 'volumes' => ['123', '456'], 'tags' => ['foo', 'bar']]
            )
            ->willReturn('{"droplets": {}}');

        $this->create(['foo', 'bar'], 'nyc1', '512mb', 123456, false, false, false, ['123', '456', '789'], '', true, ['123', '456'], ['foo', 'bar'])->shouldReturn(null);
    }

    public function it_throws_an_http_exception_if_not_possible_to_create_a_droplet($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                ['name' => 'foo', 'region' => 'nyc1', 'size' => '512mb', 'image' => 123456, 'backups' => 'false', 'ipv6' => 'false', 'private_networking' => 'false', 'monitoring' => 'true']
            )
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringCreate('foo', 'nyc1', '512mb', 123456);
    }

    public function it_deletes_the_droplet_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/droplets/123')
            ->shouldBeCalled();

        $this->delete(123);
    }

    public function it_throws_an_http_exception_when_trying_to_delete_inexisting_droplet($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/droplets/123')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDelete(123);
    }

    public function it_returns_an_array_of_droplets_kernel_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/kernels')
            ->willReturn(sprintf('{"kernels": [{},{},{}], "meta": {"total": %d}}', $total));

        $kernels = $this->getAvailableKernels(123);
        $kernels->shouldBeArray();
        $kernels->shouldHaveCount($total);
        foreach ($kernels as $kernel) {
            $kernel->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_droplets_snapshots_which_are_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/snapshots?per_page=200')
            ->willReturn(sprintf('{"snapshots": [{},{},{}], "meta": {"total": %d}}', $total));

        $snapshots = $this->getSnapshots(123);
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount($total);
        foreach ($snapshots as $snapshot) {
            $snapshot->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_droplets_backup_which_are_image_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/backups?per_page=200')
            ->willReturn(sprintf('{"backups": [{},{},{}], "meta": {"total": %d}}', $total));
        $backups = $this->getBackups(123);
        $backups->shouldBeArray();
        $backups->shouldHaveCount($total);
        foreach ($backups as $backup) {
            $backup->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_array_of_droplets_action_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/actions?per_page=200')
            ->willReturn(sprintf('{"actions": [{"region": {}}, {"region": {}}, {"region": {}}], "meta": {"total": %d}}', $total));

        $actions = $this->getActions(123);
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
            $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_the_given_droplets_action_get_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/actions/456')->willReturn('{"action": {"region": {}}}');

        $action = $this->getActionById(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_reboot($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'reboot'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->reboot(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_power_cycle($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'power_cycle'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->powerCycle(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_shutdown($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'shutdown'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->shutdown(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_power_off($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'power_off'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->powerOff(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_power_on($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'power_on'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->powerOn(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_password_reset($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'password_reset'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->passwordReset(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_resize($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                ['type' => 'resize', 'size' => '1gb', 'disk' => 'true']
            )
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->resize(123, '1gb', true);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_restore($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                ['type' => 'restore', 'image' => 456]
            )
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->restore(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_rebuild($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                ['type' => 'rebuild', 'image' => 'my-slug']
            )
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->rebuild(123, 'my-slug');
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_changing_kernel($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                ['type' => 'change_kernel', 'kernel' => 789]
            )
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->changeKernel(123, 789);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_ipv6_enabled($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'enable_ipv6'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->enableIpv6(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_backups_are_enabled($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'enable_backups'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->enableBackups(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_backups_are_disabled($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'disable_backups'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->disableBackups(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_enabling_private_network($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/droplets/123/actions', ['type' => 'enable_private_networking'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->enablePrivateNetworking(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }
}
