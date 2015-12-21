<?php

namespace spec\DigitalOceanV2\Entity;

class DropletSpec extends \PhpSpec\ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'foo_bar' => 'bar_baz',
            'baz_qmx' => 123,
            'barFoo' => [5, '5'],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Entity\Droplet');
    }
}
