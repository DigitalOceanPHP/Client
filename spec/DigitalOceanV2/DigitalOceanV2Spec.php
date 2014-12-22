<?php

namespace spec\DigitalOceanV2;

use DigitalOceanV2\Adapter\AdapterInterface;

class DigitalOceanV2Spec extends \PhpSpec\ObjectBehavior
{
    public function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\DigitalOceanV2');
    }

    public function it_should_return_an_action_instance()
    {
        $this->action()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Action');
    }

    public function it_should_return_an_image_instance()
    {
        $this->image()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Image');
    }

    public function it_should_return_a_domain_records_instance()
    {
        $this->domainRecord()->shouldBeAnInstanceOf('DigitalOceanV2\Api\DomainRecord');
    }

    public function it_should_return_a_domain_instance()
    {
        $this->domain()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Domain');
    }

    public function it_should_return_a_size_instance()
    {
        $this->size()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Size');
    }

    public function it_should_return_a_region_instance()
    {
        $this->region()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Region');
    }

    public function it_should_return_a_key_instance()
    {
        $this->key()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Key');
    }

    public function it_should_return_a_droplet_instance()
    {
        $this->droplet()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Droplet');
    }

    public function it_should_return_a_rate_limit_instance()
    {
        $this->rateLimit()->shouldBeAnInstanceOf('DigitalOceanV2\Api\RateLimit');
    }
}
