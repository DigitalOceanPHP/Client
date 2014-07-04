<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class DropletSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Droplet');
    }

    function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets')->willReturn('{"droplets": []}');

        $droplets = $this->getAll();
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount(0);
    }

    function it_returns_an_array_of_droplet_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets')->willReturn('{"droplets": [{},{},{}]}');

        $droplets = $this->getAll();
        $droplets->shouldBeArray();
        $droplets->shouldHaveCount(3);
        $droplets[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        $droplets[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
        $droplets[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
    }

    function it_returns_an_droplet_entity_get_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123')->willReturn('{"droplet": {}}');

        $this->getById(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet');
    }

    function it_throws_an_runtime_exception_if_requested_droplet_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/droplets/123456789123456789')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringGetById(123456789123456789);
    }

    function it_returns_the_created_droplet_entity_without_ssh_keys($adapter)
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

    function it_returns_the_created_droplet_entity_with_ssh_keys($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/droplets',
                array('Content-Type: application/json'),
                '{"name":"bar","region":"nyc2","size":"512mb","image":"ubuntu","backups":true,"ipv6":true,"private_networking":true,"ssh_keys":[123,456,789]}'
            )
            ->willReturn('{"droplet": {}}')
        ;

        $this
            ->create('bar', 'nyc2', '512mb', 'ubuntu', true, true, true, array(123, 456, 789))
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Droplet')
        ;
    }

    function it_thows_an_runtime_exception_if_not_possible_to_create_a_droplet($adapter)
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

    function it_deletes_the_droplet_and_returns_nothing($adapter)
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

    function it_throws_a_runtime_exception_when_trying_to_delete_inexisting_droplet($adapter)
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

    function it_returns_an_array_of_droplets_kernel_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/kernels')->willReturn('{"kernels": [{},{},{}]}');

        $kernels = $this->getAvailableKernels(123);
        $kernels->shouldBeArray();
        $kernels->shouldHaveCount(3);
        $kernels[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
        $kernels[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
        $kernels[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Kernel');
    }

    function it_returns_an_array_of_droplets_snapshots_which_are_image_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/snapshots')->willReturn('{"snapshots": [{},{},{}]}');

        $snapshots = $this->getSnapshots(123);
        $snapshots->shouldBeArray();
        $snapshots->shouldHaveCount(3);
        $snapshots[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $snapshots[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $snapshots[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_returns_an_array_of_droplets_backup_which_are_image_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/backups')->willReturn('{"backups": [{},{},{}]}');

        $backups = $this->getBackups(123);
        $backups->shouldBeArray();
        $backups->shouldHaveCount(3);
        $backups[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $backups[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
        $backups[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Image');
    }

    function it_returns_the_given_droplets_action_get_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/droplets/123/actions/456')->willReturn('{"action": {}}');

        $this->getActionById(123, 456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }
}
