<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class FloatingIpSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\FloatingIp');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/floating_ips?per_page=200')->willReturn('{"floating_ips": []}');

        $ips = $this->getAll();
        $ips->shouldBeArray();
        $ips->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_floating_ip_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/floating_ips?per_page=200')
            ->willReturn(sprintf('{"floating_ips": [{},{},{}], "meta": {"total": %d}}', $total));

        $ips = $this->getAll();
        $ips->shouldBeArray();
        $ips->shouldHaveCount($total);
        foreach ($ips as $ip) {
            $ip->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_a_floating_ip_entity_get_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/floating_ips/45.55.96.47')
            ->willReturn('
                {
                    "floating_ip": {
                        "ip": "45.55.96.47",
                        "droplet": null,
                        "region": {
                            "name": "New York 3",
                            "slug": "nyc3",
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
                        "locked": false
                   }
                }
            ');

        $ip = $this->getById('45.55.96.47');
        $ip->ip->shouldBe('45.55.96.47');
        $ip->droplet->shouldBeNull();
        $ip->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $this->getMeta()->shouldBeNull();
    }

    public function it_throws_an_http_exception_if_requested_floating_id_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/floating_ips/1234567')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringGetById(1234567);
    }

    public function it_returns_the_created_floating_id_entity_assigned_to_droplet($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/floating_ips', ['droplet_id' => 123456])
            ->willReturn('{"floating_ip": {}}');

        $this->createAssigned(123456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
    }

    public function it_returns_the_created_floating_id_entity_reserved_by_region($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/floating_ips', ['region' => 'nyc3'])
            ->willReturn('{"floating_ip": {}}');

        $this->createReserved('nyc3')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
    }

    public function it_deletes_the_floating_ip_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/floating_ips/123')
            ->shouldBeCalled();

        $this->delete(123);
    }

    public function it_throws_an_http_exception_when_trying_to_delete_inexisting_floating_ip($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/floating_ips/123')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->duringDelete(123);
    }

    public function it_returns_an_array_of_floating_ips_action_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/floating_ips/123/actions?per_page=200')
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

    public function it_returns_the_given_floating_ips_action_get_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/floating_ips/123/actions/456')->willReturn('{"action": {"region": {}}}');

        $action = $this->getActionById(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_assign($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/floating_ips/123/actions', ['type' => 'assign', 'droplet_id' => 456])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->assign(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }

    public function it_returns_the_action_entity_after_unassign($adapter)
    {
        $adapter
            ->post('https://api.digitalocean.com/v2/floating_ips/123/actions', ['type' => 'unassign'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->unassign(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }
}
