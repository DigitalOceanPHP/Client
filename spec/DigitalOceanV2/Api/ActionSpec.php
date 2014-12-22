<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class ActionSpec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Action');
    }

    public function it_returns_an_empty_array($adapter)
    {
        $adapter->get('https://api.digitalocean.com/v2/actions?per_page='.PHP_INT_MAX)->willReturn('{"actions": []}');

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount(0);
    }

    public function it_returns_an_array_of_action_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/actions?per_page='.PHP_INT_MAX)
            ->willReturn(sprintf('{"actions": [{},{},{}], "meta": {"total": %d}}', $total))
        ;

        $actions = $this->getAll();
        $actions->shouldBeArray();
        $actions->shouldHaveCount($total);
        foreach ($actions as $action) {
            $action->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Action');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    public function it_returns_an_action_entity_get_by_its_id($adapter)
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

        $this->getMeta()->shouldBeNull();
    }

    public function it_throws_an_runtime_exception_if_requested_action_does_not_exist($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/actions/123456789123456789')
            ->willThrow(new \RuntimeException('Request not processed.'))
        ;

        $this->shouldThrow(new \RuntimeException('Request not processed.'))->duringGetById(123456789123456789);
    }
}
