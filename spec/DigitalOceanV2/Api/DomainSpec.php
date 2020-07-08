<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\HttpClient\HttpClientInterface;
use DigitalOceanV2\Exception\HttpException;

class DomainSpec extends \PhpSpec\ObjectBehavior
{

    function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Domain');
    }


    function it_returns_an_empty_array(HttpClientInterface $httpClient)
    {
        $httpClient->get('https://api.digitalocean.com/v2/domains?per_page=200&page=1')->willReturn('{"domains": []}');

        $domains = $this->getAll();
        $domains->shouldBeArray();
        $domains->shouldHaveCount(0);
    }


    function it_returns_an_array_of_domain_entity(HttpClientInterface $httpClient)
    {
        $total = 3;
        $httpClient
            ->get('https://api.digitalocean.com/v2/domains?per_page=200&page=1')
            ->willReturn(sprintf('{"domains": [{},{},{}], "meta": {"total": %d}}', $total));

        $domains = $this->getAll();
        $domains->shouldBeArray();
        $domains->shouldHaveCount($total);
        foreach ($domains as $domain) {
            /**
             * @var \DigitalOceanV2\Entity\Domain|\PhpSpec\Wrapper\Subject $domain
             */
            $domain->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Domain');
        }
        $meta = $this->getMeta();
        $meta->shouldBeAnInstanceOf('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe(3);
    }


    function it_returns_a_domain_entity_get_by_its_name(HttpClientInterface $httpClient)
    {
        $httpClient
            ->get('https://api.digitalocean.com/v2/domains/foo.com')
            ->willReturn('
                {
                    "domain": {
                        "name": "foo.com",
                        "ttl": 1800,
                        "zone_file": "Example zone file text..."
                    }
                }
            ');

        $this->getByName('foo.com')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Domain');
    }


    function it_throws_an_http_exception_if_requested_domain_does_not_exist(HttpClientInterface $httpClient)
    {
        $httpClient
            ->get('https://api.digitalocean.com/v2/domains/foo.bar')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('getByName', ['foo.bar']);
    }


    function it_returns_the_created_domain_entity(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/domains', ['name' => 'bar.dk', 'ip_address' => '127.0.0.1'])
            ->willReturn('
                {
                    "domain": {
                        "name": "bar.dk",
                        "ttl": 1800,
                        "zone_file": null
                    }
                }
            ');

        $this->create('bar.dk', '127.0.0.1')->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Domain');
    }


    function it_throws_an_http_exception_if_ip_address_is_invalid(HttpClientInterface $httpClient)
    {
        $httpClient
            ->post('https://api.digitalocean.com/v2/domains', ['name' => 'boo.dk', 'ip_address' => '123456'])
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('create', ['boo.dk', '123456']);
    }


    function it_deletes_the_domain_and_returns_nothing(HttpClientInterface $httpClient)
    {
        $httpClient
            ->delete('https://api.digitalocean.com/v2/domains/qmx.fr')
            ->shouldBeCalled();

        $this->delete('qmx.fr');
    }


    function it_throws_an_http_exception_when_trying_to_delete_an_inexisting_domain(HttpClientInterface $httpClient)
    {
        $httpClient
            ->delete('https://api.digitalocean.com/v2/domains/qmx.bar')
            ->willThrow(new HttpException('Request not processed.'));

        $this->shouldThrow(new HttpException('Request not processed.'))->during('delete', ['qmx.bar']);
    }
}
