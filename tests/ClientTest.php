<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Kirk <contact@sbin.dk>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\CdnEndpoint;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Database;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\Firewall;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\LoadBalancer;
use DigitalOceanV2\Api\ProjectResource;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\ReservedIp;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;
use DigitalOceanV2\Api\Vpc;
use DigitalOceanV2\Client;
use GuzzleHttp\Psr7\Response;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class ClientTest extends TestCase
{
    public function testCreateClient(): void
    {
        $client = new Client();

        self::assertInstanceOf(Client::class, $client);
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testCreateApis(): void
    {
        $client = new Client();

        self::assertInstanceOf(Account::class, $client->account());
        self::assertInstanceOf(Action::class, $client->action());
        self::assertInstanceOf(CdnEndpoint::class, $client->cdnEndpoint());
        self::assertInstanceOf(Certificate::class, $client->certificate());
        self::assertInstanceOf(Database::class, $client->database());
        self::assertInstanceOf(Domain::class, $client->domain());
        self::assertInstanceOf(DomainRecord::class, $client->domainRecord());
        self::assertInstanceOf(Droplet::class, $client->droplet());
        self::assertInstanceOf(Firewall::class, $client->firewall());
        self::assertInstanceOf(FloatingIp::class, $client->floatingIp());
        self::assertInstanceOf(Image::class, $client->image());
        self::assertInstanceOf(Key::class, $client->key());
        self::assertInstanceOf(LoadBalancer::class, $client->loadBalancer());
        self::assertInstanceOf(ProjectResource::class, $client->projectResource());
        self::assertInstanceOf(Region::class, $client->region());
        self::assertInstanceOf(ReservedIp::class, $client->reservedIp());
        self::assertInstanceOf(Size::class, $client->size());
        self::assertInstanceOf(Snapshot::class, $client->snapshot());
        self::assertInstanceOf(Tag::class, $client->tag());
        self::assertInstanceOf(Volume::class, $client->volume());
        self::assertInstanceOf(Vpc::class, $client->vpc());
    }

    public function testAuthenticationIsSentToTheBaseUrl(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests));
        $client->authenticate('my-token');
        $client->getHttpClient()->get('/v2/account');
        $client->getHttpClient()->get('https://API.digitalocean.com:443/v2/droplets?page=2');

        self::assertCount(2, $requests);
        self::assertSame('Bearer my-token', $requests[0]->getHeaderLine('Authorization'));
        self::assertSame('Bearer my-token', $requests[1]->getHeaderLine('Authorization'));
    }

    public function testAuthenticationIsNotSentToOtherOrigins(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests));
        $client->authenticate('my-token');
        $client->getHttpClient()->get('https://cloud.digitalocean.com/v2/account');
        $client->getHttpClient()->get('http://api.digitalocean.com/v2/account');
        $client->getHttpClient()->get('https://api.digitalocean.com:8443/v2/account');
        $client->getHttpClient()->get('https://api.digitalocean.com.example.com/v2/account');

        self::assertCount(4, $requests);

        foreach ($requests as $request) {
            self::assertFalse($request->hasHeader('Authorization'), (string) $request->getUri());
        }
    }

    public function testAuthenticationIsRemovedFromRequestsToOtherOrigins(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests));
        $client->authenticate('my-token');
        $client->getHttpClient()->get('https://digitalocean.example.com/v2/account', ['Authorization' => 'Bearer my-token']);
        $client->getHttpClient()->get('https://digitalocean.example.com/v2/account', ['Authorization' => 'Bearer other-token']);

        self::assertFalse($requests[0]->hasHeader('Authorization'));
        self::assertSame('Bearer other-token', $requests[1]->getHeaderLine('Authorization'));
    }

    public function testAuthenticationFollowsTheBaseUrl(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests));
        $client->authenticate('my-token');
        $client->setUrl('https://digitalocean.example.com');
        $client->getHttpClient()->get('/v2/account');
        $client->getHttpClient()->get('https://api.digitalocean.com/v2/account');

        self::assertSame('digitalocean.example.com', $requests[0]->getUri()->getHost());
        self::assertSame('Bearer my-token', $requests[0]->getHeaderLine('Authorization'));
        self::assertFalse($requests[1]->hasHeader('Authorization'));
    }

    public function testAuthenticationIsSentToTheBaseUrlOnRedirect(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests, new Response(302, ['Location' => 'https://api.digitalocean.com/v2/droplets/2'])));
        $client->authenticate('my-token');
        $client->getHttpClient()->get('/v2/droplets/1');

        self::assertSame('/v2/droplets/2', $requests[1]->getUri()->getPath());
        self::assertSame('Bearer my-token', $requests[1]->getHeaderLine('Authorization'));
    }

    public function testAuthenticationIsNotSentToOtherOriginsOnRedirect(): void
    {
        $requests = [];

        $client = Client::createWithHttpClient($this->createHttpClient($requests, new Response(302, ['Location' => 'https://digitalocean.example.com/v2/droplets/1'])));
        $client->authenticate('my-token');
        $client->getHttpClient()->get('/v2/droplets/1');

        self::assertSame('Bearer my-token', $requests[0]->getHeaderLine('Authorization'));
        self::assertSame('digitalocean.example.com', $requests[1]->getUri()->getHost());
        self::assertFalse($requests[1]->hasHeader('Authorization'));
    }

    /**
     * @param RequestInterface[] $requests
     */
    private function createHttpClient(array &$requests, Response ...$responses): ClientInterface
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::any())
            ->method('sendRequest')
            ->willReturnCallback(static function (RequestInterface $request) use (&$requests, &$responses): Response {
                $requests[] = $request;

                return \array_shift($responses) ?? new Response();
            });

        return $httpClient;
    }
}
