<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class KeySpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Key');
    }

    function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/account/keys')->willReturn('{"ssh_keys": []}');

        $keys = $this->getAll();
        $keys->shouldBeArray();
        $keys->shouldHaveCount(0);
    }

    function it_returns_an_array_of_key_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/account/keys')->willReturn('{"ssh_keys": [{},{},{}]}');

        $keys = $this->getAll();
        $keys->shouldBeArray();
        $keys->shouldHaveCount(3);
        $keys[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
        $keys[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
        $keys[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
    }

    function it_returns_a_key_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/account/keys/123')
            ->willReturn('
                {
                    "ssh_key": {
                        "id": 123,
                        "fingerprint": "f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2",
                        "public_key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....",
                        "name": "qmx"
                    }
                }
            ')
        ;

        $this->getById(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
    }

    function it_returns_a_key_entity_get_by_its_fingerprint($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/account/keys/f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2')
            ->willReturn('
                {
                    "ssh_key": {
                        "id": 123,
                        "fingerprint": "f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2",
                        "public_key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....",
                        "name": "qmx"
                    }
                }
            ')
        ;

        $this
            ->getByFingerprint('f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key')
        ;
    }

    function it_returns_the_created_key($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/account/keys',
                array('Content-Type: application/json'),
                '{"name":"foo", "public_key":"ssh-rsa foobarbaz..."}'
            )
            ->willReturn('
                {
                    "ssh_key": {
                        "id": 999,
                        "fingerprint": "f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2",
                        "public_key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....",
                        "name": "foo"
                    }
                }
            ')
        ;

        $this->create('foo', 'ssh-rsa foobarbaz...')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
    }

    function it_returns_the_updated_key($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/account/keys/456',
                array('Content-Type: application/json'),
                '{"name":"bar"}'
            )
            ->willReturn('
                {
                    "ssh_key": {
                        "id": 456,
                        "fingerprint": "f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2",
                        "public_key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....",
                        "name": "bar"
                    }
                }
            ')
        ;

        $this->update(456, 'bar')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Key');
    }

    function it_throws_a_runtime_exception_when_trying_to_update_an_inexisting_key($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/account/keys/0',
                array('Content-Type: application/json'),
                '{"name":"baz"}'
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('update', array(0, 'baz'));
    }

    function it_deletes_the_key_and_returns_nothing($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/account/keys/678',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willReturn('foo')
        ;

        $this->delete(678);
    }

    function it_throws_a_runtime_exception_when_trying_to_delete_an_inexisting_key($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/account/keys/0',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->during('delete', array(0));
    }
}
