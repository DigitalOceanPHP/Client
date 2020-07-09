<?php

declare(strict_types=1);

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;
use DigitalOceanV2\Exception\RuntimeException;

class ActionSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Action');
    }


    function it_returns_an_empty_array(HttpClientInterface $httpClient)
    {
        $httpClient->get('https://api.digitalocean.com/v2/actions?per_page=200')->willReturn('{"actions": []}');

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount(0);
    }


    function it_returns_an_array_of_action_entity(HttpClientInterface $httpClient)
    {
        $total = 3;
        $httpClient
            ->get('https://api.digitalocean.com/v2/actions?per_page=200')
            ->willReturn(sprintf(
                '
                {
                    "actions": [
                        {
                            "id": 1,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 1,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        },
                        {
                            "id": 2,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 2,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        },
                        {
                            "id": 3,
                            "status": "completed",
                            "type": "rename",
                            "started_at": "2014-06-10T22:25:14Z",
                            "completed_at": "2014-06-10T23:25:14Z",
                            "resource_id": 3,
                            "resource_type": "droplet",
                            "region": {
                                "name": "New York 3",
                                "slug": "nyc3",
                                "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                                "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                                "available": true
                            },
                            "region_slug": "nyc2"
                        }
                    ],
                    "meta": {
                        "total": %d
                    }
                }
            ',
                $total
            ));

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            /**
             * @var \DigitalOceanV2\Entity\Action|\PhpSpec\Wrapper\Subject $action
             */
            $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');

            /**
             * @var \DigitalOceanV2\Entity\Region|\PhpSpec\Wrapper\Subject $actionRegion
             */
            $actionRegion = $action->region;
            $actionRegion->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }


    function it_returns_an_action_entity_get_by_its_id(HttpClientInterface $httpClient)
    {
        $httpClient
            ->get('https://api.digitalocean.com/v2/actions/123')
            ->willReturn('
                {
                    "action": {
                        "id": 123,
                        "status": "completed",
                        "type": "rename",
                        "started_at": "2014-06-10T22:25:14Z",
                        "completed_at": "2014-06-10T23:25:14Z",
                        "resource_id": 123,
                        "resource_type": "droplet",
                        "region": {
                            "name": "New York 3",
                            "slug": "nyc3",
                            "sizes": [ "32gb", "16gb", "2gb", "1gb", "4gb", "8gb", "512mb", "64gb", "48gb" ],
                            "features": [ "virtio", "private_networking", "backups", "ipv6", "metadata" ],
                            "available": true
                        },
                        "region_slug": "nyc2"
                    }
                }
            ');

        $action = $this->getById(123);
        $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $action->region->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Region');
        $action->regionSlug->shouldReturn('nyc2');

        $this->getMeta()->shouldBeNull();
    }


    function it_throws_an_http_exception_if_requested_action_does_not_exist(HttpClientInterface $httpClient)
    {
        $httpClient
            ->get('https://api.digitalocean.com/v2/actions/1234567')
            ->willThrow(new RuntimeException('Request not processed.'));

        $this->shouldThrow(new RuntimeException('Request not processed.'))->during('getById', [1234567]);
    }
}
