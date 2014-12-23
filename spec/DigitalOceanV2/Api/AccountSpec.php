<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class AccountSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Account');
    }

    function it_returns_user_information($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/account')
            ->willReturn('
                {
                    "account": {
                        "droplet_limit": 10,
                        "email": "contact@sbin.dk",
                        "uuid": "fdskjjk7543jsa997342j",
                        "email_verified": true
                    }
                }
            ');

        $this->getUserInformation()->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Account');
    }
}
