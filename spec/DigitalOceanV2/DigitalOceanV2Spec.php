<?php

namespace spec\DigitalOceanV2;

use DigitalOceanV2\Adapter\AdapterInterface;

class DigitalOceanV2Spec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\DigitalOceanV2');
    }

    function it_should_return_an_account_instance()
    {
        $this->account()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Account');
    }

    function it_should_return_an_action_instance()
    {
        $this->action()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Action');
    }

    function it_should_return_a_certificate_instance()
    {
        $this->certificate()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Certificate');
    }

    function it_should_return_a_domain_instance()
    {
        $this->domain()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Domain');
    }

    function it_should_return_a_domain_records_instance()
    {
        $this->domainRecord()->shouldBeAnInstanceOf('DigitalOceanV2\Api\DomainRecord');
    }

    function it_should_return_a_droplet_instance()
    {
        $this->droplet()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Droplet');
    }

    function it_should_return_a_floating_ip_instance()
    {
        $this->floatingIp()->shouldBeAnInstanceOf('DigitalOceanV2\Api\FloatingIp');
    }

    function it_should_return_an_image_instance()
    {
        $this->image()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Image');
    }

    function it_should_return_a_key_instance()
    {
        $this->key()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Key');
    }

    function it_should_return_a_load_balancer_instance()
    {
        $this->loadBalancer()->shouldBeAnInstanceOf('DigitalOceanV2\Api\LoadBalancer');
    }

    function it_should_return_a_rate_limit_instance()
    {
        $this->rateLimit()->shouldBeAnInstanceOf('DigitalOceanV2\Api\RateLimit');
    }

    function it_should_return_a_region_instance()
    {
        $this->region()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Region');
    }

    function it_should_return_a_size_instance()
    {
        $this->size()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Size');
    }

    function it_should_return_a_volume_instance()
    {
        $this->volume()->shouldBeAnInstanceOf('DigitalOceanV2\Api\Volume');
    }
}
