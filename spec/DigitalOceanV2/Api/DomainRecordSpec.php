<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class DomainRecordSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\DomainRecord');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/domains/foo.dk/records?per_page='.PHP_INT_MAX)->willReturn('{"domain_records": []}');

        $domainRecords = $this->getAll('foo.dk');
        $domainRecords->shouldBeArray();
        $domainRecords->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_domain_record_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/domains/foo.dk/records?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"domain_records": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

        $domainRecords = $this->getAll('foo.dk');
        $domainRecords->shouldBeArray();
        $domainRecords->shouldHaveCount($total);
        foreach ($domainRecords as $domainRecord) {
            $domainRecord->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_the_domain_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/domains/foo.dk/records/123')
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "CNAME",
                        "name": "example",
                        "data": "@",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this->getById('foo.dk', 123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord');
        $this->getMeta()->shouldBeNull();
    }

    public function it_throws_an_runtime_exception_if_requested_domain_record_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/domains/foo.dk/records/123456789')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringGetById('foo.dk', 123456789);
    }

    public function it_returns_the_created_domain_record_type_a($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"name":"@", "type":"A", "data":"8.8.8.8"}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "A",
                        "name": "@",
                        "data": "8.8.8.8",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this->create('foo.dk', 'a', '@', '8.8.8.8')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord');
    }

    public function it_returns_the_created_domain_record_type_aaaa($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"name":"ipv6host", "type":"AAAA", "data":"2001:db8::ff00:42:8329"}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "A",
                        "name": "ipv6host",
                        "data": "8.8.8.8",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'aaaa', 'ipv6host', '2001:db8::ff00:42:8329')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_returns_the_created_domain_record_type_cname($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"name":"newalias", "type":"CNAME", "data":"hosttarget"}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "CNAME",
                        "name": "newalias",
                        "data": "hosttarget",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'cname', 'newalias', 'hosttarget')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_returns_the_created_domain_record_type_txt($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"name":"recordname", "type":"TXT", "data":"whatever"}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "TXT",
                        "name": "recordname",
                        "data": "whatever",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'txt', 'recordname', 'whatever')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_returns_the_created_domain_record_type_ns($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"type":"NS", "data":"ns1.digitalocean.com."}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "NS",
                        "name": null,
                        "data": "ns1.digitalocean.com.",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'ns', 'not_used', 'ns1.digitalocean.com.')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_returns_the_created_domain_record_type_srv($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"name":"servicename", "type":"SRV", "data":"targethost", "priority":0, "port":1, "weight":2}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "SRV",
                        "name": "servicename",
                        "data": "targethost",
                        "priority": 0,
                        "port": 1,
                        "weight": 2
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'srv', 'servicename', 'targethost', 0, 1, 2)
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_returns_the_created_domain_record_type_mx($adapter)
    {
        $adapter
            ->post(
                'https://api.digitalocean.com/v2/domains/foo.dk/records',
                array('Content-Type: application/json'),
                '{"type":"MX", "data":"127.0.0.1", "priority":0}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 123,
                        "type": "MX",
                        "name": null,
                        "data": "127.0.0.1",
                        "priority": 0,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->create('foo.dk', 'mx', 'not_used', '127.0.0.1', 0)
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_throws_an_runtime_exception_if_unknown_type($adapter)
    {
        $this
            ->shouldThrow(new \RuntimeException('Domain record type is invalid.'))
            ->duringCreate('foo.dk', 'foo_bar', 'name', 'data')
        ;
    }

    public function it_returns_updated_domain_record($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/domains/foo.dk/records/456',
                array('Content-Type: application/json'),
                '{"name":"new-name"}'
            )
            ->willReturn('
                {
                    "domain_record": {
                        "id": 456,
                        "type": "A",
                        "name": "new-name",
                        "data": "127.0.0.1",
                        "priority": null,
                        "port": null,
                        "weight": null
                    }
                }
            ')
        ;

        $this
            ->update('foo.dk', 456, 'new-name')
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\DomainRecord')
        ;
    }

    public function it_throws_an_runtime_exception_when_trying_to_update_inexisting_domain_record($adapter)
    {
        $adapter
            ->put(
                'https://api.digitalocean.com/v2/domains/foo.dk/records/123',
                array('Content-Type: application/json'),
                '{"name":"new-name"}'
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringUpdate('foo.dk', 123, 'new-name');
    }

    public function it_deletes_given_domain_record_and_returns_nothing($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/domains/foo.dk/records/123',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->shouldBeCalled()
        ;

        $this->delete('foo.dk', 123);
    }

    public function it_thorws_an_runtime_exception_when_trying_to_delete_inexisting_domain_record($adapter)
    {
        $adapter
            ->delete(
                'https://api.digitalocean.com/v2/domains/foo.dk/records/123',
                array('Content-Type: application/x-www-form-urlencoded')
            )
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringDelete('foo.dk', 123);
    }
}
