<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class ActionSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Action');
    }

    function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/actions')->willReturn('{"actions": []}');

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount(0);
    }

    function it_returns_an_array_of_action_entity($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/actions')->willReturn('{"actions": [{},{},{}]}');

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount(3);
        $actions[0]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $actions[1]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        $actions[2]->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    function it_returns_an_action_entity_get_by_its_id($adapter)
    {
        $adapter
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
                        "region": "nyc2"
                    }
                }
            ')
        ;

        $this->getById(123)->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
    }

    function if_throws_an_runtime_exception_if_requested_action_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/actions/123456789123456789')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringGetById(123456789123456789);
    }
}
