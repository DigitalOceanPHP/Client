<?php

declare(strict_types=1);

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;
use DigitalOceanV2\Exception\RuntimeException;

class FloatingIpSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\FloatingIp');
    }


    function it_returns_an_empty_array(HttpClientInterface $httpClient)
    {
        $httpClient->get('https://api.digitalocean.com/v2/floating_ips?per_page=200')->willReturn('{"floating_ips": []}');

        $ips = $this->getAll();
        $ips->shouldBeArray();
        $ips->shouldHaveCount(0);
    }


    function it_returns_an_array_of_floating_ip_entity(HttpClientInterface $httpClient)
    {
        $total = 3;
        $httpClient
            ->get('https://api.digitalocean.com/v2/floating_ips?per_page=200')
            ->willReturn(sprintf('{"floating_ips": [{},{},{}], "meta": {"total": %d}}', $total));

        $ips = $this->getAll();
        $ips->shouldBeArray();
        $ips->shouldHaveCount($total);
        foreach ($ips as $ip) {
            /**
             * @var \DigitalOceanV2\Entity\FloatingIp|\PhpSpec\Wrapper\Subject $ip
             */
            $ip->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }


    function it_returns_a_floating_ip_entity_get_by_its_id(HttpClientInterface $httpClient)
    {
        $httpClient
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


    function it_throws_an_http_exception_if_requested_floating_id_does_not_exist(HttpClientInterface $httpClient)
    {
        $httpClient
            ->get('https://api.digitalocean.com/v2/floating_ips/1234567')
            ->willThrow(new RuntimeException('Request not processed.'));

        $this->shouldThrow(new RuntimeException('Request not processed.'))->during('getById', [1234567]);
    }


    function it_returns_the_created_floating_id_entity_assigned_to_droplet(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/floating_ips', ['droplet_id' => 123456])
            ->willReturn('{"floating_ip": {}}');

        $this->createAssigned(123456)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
    }


    function it_returns_the_created_floating_id_entity_reserved_by_region(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/floating_ips', ['region' => 'nyc3'])
            ->willReturn('{"floating_ip": {}}');

        $this->createReserved('nyc3')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\FloatingIp');
    }


    function it_deletes_the_floating_ip_and_returns_nothing(HttpClientInterface $httpClient)
    {
        $httpClient
            ->delete('https://api.digitalocean.com/v2/floating_ips/123')
            ->shouldBeCalled();

        $this->delete(123);
    }


    function it_throws_an_http_exception_when_trying_to_delete_inexisting_floating_ip(HttpClientInterface $httpClient)
    {
        $httpClient
            ->delete('https://api.digitalocean.com/v2/floating_ips/123')
            ->willThrow(new RuntimeException('Request not processed.'));

        $this->shouldThrow(new RuntimeException('Request not processed.'))->during('delete', [123]);
    }


    function it_returns_an_array_of_floating_ips_action_entity(HttpClientInterface $httpClient)
    {
        $total = 3;
        $httpClient
            ->get('https://api.digitalocean.com/v2/floating_ips/123/actions?per_page=200')
            ->willReturn(sprintf('{"actions": [{"region": {}}, {"region": {}}, {"region": {}}], "meta": {"total": %d}}', $total));

        $actions = $this->getActions(123);
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            /**
             * @var \DigitalOceanV2\Entity\Action|\PhpSpec\Wrapper\Subject $action
             */
            $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
            $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }


    function it_returns_the_given_floating_ips_action_get_by_its_id(HttpClientInterface $httpClient)
    {
        $httpClient->get('https://api.digitalocean.com/v2/floating_ips/123/actions/456')->willReturn('{"action": {"region": {}}}');

        $action = $this->getActionById(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }


    function it_returns_the_action_entity_after_assign(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/floating_ips/123/actions', ['type' => 'assign', 'droplet_id' => 456])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->assign(123, 456);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }


    function it_returns_the_action_entity_after_unassign(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/floating_ips/123/actions', ['type' => 'unassign'])
            ->willReturn('{"action": {"region": {}}}');

        $action = $this->unassign(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
    }
}
