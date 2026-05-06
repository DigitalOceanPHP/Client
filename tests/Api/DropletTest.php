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

namespace DigitalOceanV2\Tests\Api;

use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Client;
use DigitalOceanV2\Entity\Droplet as DropletEntity;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class DropletTest extends TestCase
{
    public function testItCreatesAnArrayOfDropletEntities(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets')->getAll();

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    public function testItFiltersDropletsByTagName(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets?tag_name=awesome')->getAll('awesome');

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    public function testItFiltersDropletsByName(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets?name=example.com')->getAll(name: 'example.com');

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    public function testItFiltersDropletsByType(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets?type=gpus')->getAll(type: 'gpus');

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    public function testItFiltersDropletsByStandardType(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets?type=droplets')->getAll(type: 'droplets');

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    public function testItFiltersDropletsByNameAndType(): void
    {
        $droplets = $this->createApiExpectingGet('/v2/droplets?name=example.com&type=gpus')
            ->getAll(name: 'example.com', type: 'gpus');

        self::assertInstanceOf(DropletEntity::class, $droplets[0]);
        self::assertSame('example.com', $droplets[0]->name);
    }

    private function createApiExpectingGet(string $uri): Droplet
    {
        $client = $this->createMock(Client::class);
        $client->expects(self::once())
            ->method('getHttpClient')
            ->willReturn($httpClient = $this->createMock(HttpMethodsClientInterface::class));

        $httpClient->expects(self::once())
            ->method('get')
            ->with($uri)
            ->willReturn(self::createResponse());

        return new Droplet($client);
    }

    private static function createResponse(): Response
    {
        return new Response(
            200,
            ['Content-Type' => ['application/json']],
            Utils::streamFor(\json_encode(['droplets' => [
                [
                    'id' => 3164444,
                    'name' => 'example.com',
                    'features' => [],
                ],
            ]]))
        );
    }
}
