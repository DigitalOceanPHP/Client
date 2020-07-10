<?php

declare(strict_types=1);

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Api;
use DigitalOceanV2\Client;
use DigitalOceanV2\HttpClient\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ClientTest extends TestCase
{
    public function testCreateClient()
    {
        $client = new Client();

        self::assertInstanceOf(Client::class, $client);
        self::assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testCreateApis()
    {
        $client = new Client();

        self::assertInstanceOf(Api\Account::class, $client->account());
        self::assertInstanceOf(Api\Action::class, $client->action());
        self::assertInstanceOf(Api\Certificate::class, $client->certificate());
        self::assertInstanceOf(Api\Domain::class, $client->domain());
        self::assertInstanceOf(Api\DomainRecord::class, $client->domainRecord());
        self::assertInstanceOf(Api\Droplet::class, $client->droplet());
        self::assertInstanceOf(Api\FloatingIp::class, $client->floatingIp());
        self::assertInstanceOf(Api\Image::class, $client->image());
        self::assertInstanceOf(Api\Key::class, $client->key());
        self::assertInstanceOf(Api\LoadBalancer::class, $client->loadBalancer());
        self::assertInstanceOf(Api\Region::class, $client->region());
        self::assertInstanceOf(Api\Size::class, $client->size());
        self::assertInstanceOf(Api\Snapshot::class, $client->snapshot());
        self::assertInstanceOf(Api\Tag::class, $client->tag());
        self::assertInstanceOf(Api\Volume::class, $client->volume());
    }
}
