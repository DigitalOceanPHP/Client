<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

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
        $adapter->get('https://api.digitalocean.com/v2/droplets?per_page='.PHP_INT_MAX)->willReturn('{"droplets": []}');

        $droplets = $this->getAll();
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_droplet_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"droplets": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

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

    public function it_returns_an_droplet_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123')
            ->willReturn('
                {
                    "droplet": {
                        "id": 14,
                        "name": "test.example.com",
                        "region": {
                            "slug": "nyc2",
                            "name": "New York",
                            "sizes": [
                                "1024mb",
                                "512mb"
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
                        "sizeSlug": "512mb",
                        "locked": false,
                        "created_at": "2014-07-02T15:22:06Z",
                        "features": [
                            "virtio",
                            "private_networking",
                            "backups",
                            "ipv6"
                        ],
                        "status": "active",
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
                                    "cidr": 124,
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
                        "action_ids": []
                    }
                }
            ')
        ;

        $droplet = $this->getById(123);
        $droplet->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
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
    }

    public function it_throws_an_runtime_exception_if_requested_droplet_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123456789123456789')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringGetById(123456789123456789);
    }

    public function it_returns_the_created_droplet_entity_without_ssh_keys($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                array('Content-Type: application/json'),
                '{"name":"foo","region":"nyc1","size":"512mb","image":123456,"backups":false,"ipv6":false,"private_networking":false}'
            )
            ->willReturn('{"droplet": {}}')
        ;

        $this->create('foo', 'nyc1', '512mb', 123456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
    }

    public function it_returns_the_created_droplet_entity_with_ssh_keys($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                array('Content-Type: application/json'),
                '{"name":"bar","region":"nyc2","size":"512mb","image":"ubuntu","backups":true,"ipv6":true,"private_networking":true,"ssh_keys":["123","456","789"]}'
            )
            ->willReturn('{"droplet": {}}')
        ;

        $this
            ->create('bar', 'nyc2', '512mb', 'ubuntu', true, true, true, array(123, 456, 789))
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet')
        ;
    }

    public function it_thows_an_runtime_exception_if_not_possible_to_create_a_droplet($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                array('Content-Type: application/json'),
                '{"name":"foo","region":"nyc1","size":"512mb","image":123456,"backups":false,"ipv6":false,"private_networking":false}'
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringCreate('foo', 'nyc1', '512mb', 123456);
    }

    public function it_deletes_the_droplet_and_returns_nothing($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/droplets/123',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->shouldBeCalled()
        ;

        $this->delete(123);
    }

    public function it_throws_a_runtime_exception_when_trying_to_delete_inexisting_droplet($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/droplets/123',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringDelete(123);
    }

    public function it_returns_an_array_of_droplets_kernel_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123/kernels')
            ->willReturn(sprintf('{"kernels": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

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
            ->get('https://api.digitalocean.com/v2/droplets/123/snapshots?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"snapshots": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

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
            ->get('https://api.digitalocean.com/v2/droplets/123/backups?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"backups": [{},{},{}], "meta": {"total": %d}}', $total))
        ;
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
            ->get('https://api.digitalocean.com/v2/droplets/123/actions?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"actions": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

        $actions = $this->getActions(123);
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_the_given_droplets_action_get_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/actions/456')->willReturn('{"action": {}}');

        $this->getActionById(123, 456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_reboot($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"reboot"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->reboot(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_power_cycle($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"power_cycle"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->powerCycle(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_shutdown($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"shutdown"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->shutdown(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_power_off($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"power_off"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->powerOff(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_power_on($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"power_on"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->powerOn(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_password_reset($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"password_reset"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->passwordReset(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_resize($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"resize","size":"1024mb"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->resize(123, '1024mb')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_restore($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"restore","image":456}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->restore(123, 456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_rebuild($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"rebuild","image":"my-slug"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->rebuild(123, 'my-slug')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_changing_kernel($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"change_kernel","kernel":789}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->changeKernel(123, 789)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_ipv6_enabled($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"enable_ipv6"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->enableIpv6(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_backups_are_disabled($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"disable_backups"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->disableBackups(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    public function it_returns_the_action_entity_after_enabling_private_network($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets/123/actions',
                array('Content-Type: application/json'),
                '{"type":"enable_private_networking"}'
            )
            ->willReturn('{"action": {}}')
        ;

        $this->enablePrivateNetworking(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }
}
