<?php

namespace spec\DigitalOceanV2\Entity;

class DropletSpec extends \PhpSpec\ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'foo_bar' => 'bar_baz',
            'baz_qmx' => 123,
            'barFoo'  => [5, '5'],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Entity\Droplet');
    }

    function it_can_be_build_with_unknown_properties()
    {
        $this->shouldHaveType('DigitalOceanV2\Entity\Droplet');
        $this->fooBar->shouldBe('bar_baz');
        $this->bazQmx->shouldBe(123);
        $this->barFoo->shouldBeArray();
        $this->barFoo->shouldHaveCount(2);
        $this->barFoo[0]->shouldBe(5);
        $this->barFoo[1]->shouldBe('5');
    }

    function it_can_expose_unknown_properties()
    {
        $this->shouldHaveType('DigitalOceanV2\Entity\Droplet');
        $unknownProperties = $this->getUnknownProperties();
        $unknownProperties->shouldBeArray();
        $unknownProperties->shouldHaveCount(3);
        $unknownProperties['fooBar']->shouldBe('bar_baz');
        $unknownProperties['bazQmx']->shouldBe(123);
        $unknownProperties['barFoo']->shouldBeArray();
        $unknownProperties['barFoo']->shouldHaveCount(2);
        $unknownProperties['barFoo'][0]->shouldBe(5);
        $unknownProperties['barFoo'][1]->shouldBe('5');
    }
}
