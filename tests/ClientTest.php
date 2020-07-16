<?php

declare(strict_types=1);

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\LoadBalancer;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;
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

        self::assertInstanceOf(Account::class, $client->account());
        self::assertInstanceOf(Action::class, $client->action());
        self::assertInstanceOf(Certificate::class, $client->certificate());
        self::assertInstanceOf(Domain::class, $client->domain());
        self::assertInstanceOf(DomainRecord::class, $client->domainRecord());
        self::assertInstanceOf(Droplet::class, $client->droplet());
        self::assertInstanceOf(FloatingIp::class, $client->floatingIp());
        self::assertInstanceOf(Image::class, $client->image());
        self::assertInstanceOf(Key::class, $client->key());
        self::assertInstanceOf(LoadBalancer::class, $client->loadBalancer());
        self::assertInstanceOf(Region::class, $client->region());
        self::assertInstanceOf(Size::class, $client->size());
        self::assertInstanceOf(Snapshot::class, $client->snapshot());
        self::assertInstanceOf(Tag::class, $client->tag());
        self::assertInstanceOf(Volume::class, $client->volume());
    }
}
