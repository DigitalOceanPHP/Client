<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Exception\HttpException;

class LoadBalancerSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\LoadBalancer');
    }

    public function it_throws_an_http_exception_if_load_balancer_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/load_balancers/1234')
            ->willThrow(new HttpException('Load Balancer not found'));

        $this->shouldThrow(new HttpException('Load Balancer not found'))->duringGetById(1234);
    }

    public function it_returns_an_array_of_load_balancer_entity($adapter)
    {
        $total = 3;
        $adapter->get('https://api.digitalocean.com/v2/load_balancers')
                ->willReturn(json_encode([
                    'load_balancers' => [
                        [],
                        [],
                        [],
                    ],
                    'links' => [],
                    'meta' => [
                        'total' => $total,
                    ],
                ]));

        $loadBalancers = $this->getAll();
        $loadBalancers->shouldBeArray();
        $loadBalancers->shouldHaveCount($total);
        foreach ($loadBalancers as $loadBalancer) {
            $loadBalancer->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\LoadBalancer');
        }

        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_a_load_balancer_entity_by_its_id($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/load_balancers/1234')
                ->willReturn(json_encode($this->getLoadBalancerSpecification()));

        $loadBalancer = $this->getById('1234');
        $loadBalancer->shouldBeAnInstanceOf('DigitalOceanV2\Entity\LoadBalancer');
    }

    public function it_returns_a_created_load_balancer($adapter)
    {
        $loadBalancerSpecification = $this->getLoadBalancerSpecification();

        $lbs = $loadBalancerSpecification['load_balancer'];
        $data = [
            'name' => $lbs['name'],
            'algorithm' => 'round_robin',
            'region' => 'nyc1',
            'forwarding_rules' => $lbs['forwarding_rules'],
            'health_check' => [],
            'sticky_sessions' => [],
            'droplet_ids' => [],
            'redirect_http_to_https' => false,
        ];

        $adapter
            ->post('https://api.digitalocean.com/v2/load_balancers', $data)
            ->willReturn(json_encode($loadBalancerSpecification));

        $loadBalancer = $this->create('example-lb-01', 'nyc1', $lbs['forwarding_rules']);
        $loadBalancer->shouldBeAnInstanceOf('DigitalOceanV2\Entity\LoadBalancer');
    }

    public function it_updates_an_existing_load_balancer($adapter)
    {
        $loadBalancerSpecification = $this->getLoadBalancerSpecification();
        $lbs = $loadBalancerSpecification['load_balancer'];
        $data = [
            'name' => $lbs['name'],
            'algorithm' => 'round_robin',
            'region' => 'nyc1',
            'forwarding_rules' => $lbs['forwarding_rules'],
            'health_check' => [],
            'sticky_sessions' => [],
            'droplet_ids' => [],
            'redirect_http_to_https' => false,
        ];

        $adapter
            ->put('https://api.digitalocean.com/v2/load_balancers/'.$lbs['id'], $data)
            ->willReturn(json_encode($loadBalancerSpecification));

        $loadBalancer = $this->update($lbs['id'], $data);
        $loadBalancer->shouldBeAnInstanceOf('DigitalOceanV2\Entity\LoadBalancer');
    }

    /**
     * @return string
     */
    private function getLoadBalancerSpecification()
    {
        return [
            'load_balancer' => [
                'id' => '1234',
                'name' => 'example-lb-01',
                'ip' => '104.131.186.241',
                'algorithm' => 'round_robin',
                'status' => 'new',
                'created_at' => '2017-02-01T22:22:58Z',
                'forwarding_rules' => [
                    [
                        'entry_protocol' => 'http',
                        'entry_port' => 80,
                        'target_protocol' => 'http',
                        'target_port' => 80,
                        'certificate_id' => '',
                        'tls_passthrough' => false,
                    ],
                    [
                        'entry_protocol' => 'https',
                        'entry_port' => 444,
                        'target_protocol' => 'https',
                        'target_port' => 443,
                        'certificate_id' => '',
                        'tls_passthrough' => true,
                    ],
                ],
                'health_check' => [
                    'protocol' => 'http',
                    'port' => 80,
                    'path' => '/',
                    'check_interval_seconds' => 10,
                    'response_timeout_seconds' => 5,
                    'healthy_threshold' => 5,
                    'unhealthy_threshold' => 3,
                ],
                'sticky_sessions' => [
                    'type' => 'none',
                ],
                'region' => [
                    'name' => 'New York 3',
                    'slug' => 'nyc3',
                    'sizes' => [
                        '512mb',
                        '1gb',
                        '2gb',
                        '4gb',
                        '8gb',
                        '16gb',
                        'm-16gb',
                        '32gb',
                        'm-32gb',
                        '48gb',
                        'm-64gb',
                        '64gb',
                        'm-128gb',
                        'm-224gb',
                    ],
                    'features' => [
                        'private_networking',
                        'backups',
                        'ipv6',
                        'metadata',
                        'install_agent',
                    ],
                    'available' => true,
                ],
                'tag' => '',
                'droplet_ids' => [
                    3164444,
                    3164445,
                ],
                'redirect_http_to_https' => false,
            ],
        ];
    }
}
