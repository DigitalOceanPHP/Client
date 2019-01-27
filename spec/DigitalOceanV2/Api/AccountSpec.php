<?php

namespace spec\DigitalOceanV2\Api;

class AccountSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function let($adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Account');
    }

    /**
     * @param \DigitalOceanV2\Adapter\AdapterInterface $adapter
     */
    function it_returns_user_information($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/account')
            ->willReturn('
                {
                    "account": {
                        "droplet_limit": 25,
                        "floating_ip_limit": 5,
                        "email": "sammy@digitalocean.com",
                        "uuid": "b6fr89dbf6d9156cace5f3c78dc9851d957381ef",
                        "email_verified": true,
                        "status": "active",
                        "status_message": ""
                    }
                }
            ');

        $this->getUserInformation()->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Account');
    }
}
