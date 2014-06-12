<?php

namespace spec\DigitalOceanV2;

use DigitalOceanV2\Adapter\AdapterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DigitalOceanV2Spec extends ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\DigitalOceanV2');
    }

    function it_should_return_an_action_instance()
    {
        $this->action()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Action');
    }
}
