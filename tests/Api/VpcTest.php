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

use DigitalOceanV2\Api\Vpc;
use DigitalOceanV2\Client;
use DigitalOceanV2\Entity\Vpc as VpcEntity;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Manuel Christlieb <manuel@gchristlieb.eu>
 */
class VpcTest extends TestCase
{
    public function testItCreatesAnArrayOfVpcEntities(): void
    {
        $client = $this->createMock(Client::class);
        $client->expects(self::once())
            ->method('getHttpClient')
            ->willReturn($httpClient = $this->createMock(HttpMethodsClientInterface::class));
        $httpClient->expects(self::once())
            ->method('get')
            ->with('/v2/vpcs')
            ->willReturn(new Response(
                200,
                ['Content-Type' => ['application/json']],
                Utils::streamFor(\json_encode(['vpcs' => [
                    [
                        'name' => 'env.prod-vpc',
                        'description' => 'VPC for production environment',
                        'region' => 'nyc1',
                        'ip_range' => '10.10.10.0/24',
                        'id' => '5a4981aa-9653-4bd1-bef5-d6bff52042e4',
                        'urn' => 'do:vpc:5a4981aa-9653-4bd1-bef5-d6bff52042e4',
                        'default' => false,
                        'created_at' => '2020-03-13T19:20:47.442049222Z',
                    ],
                ]]))
            ));
        $vpcApi = new Vpc($client);
        $vpcs = $vpcApi->getAll();

        self::assertInstanceOf(VpcEntity::class, $vpcs[0]);
    }
}
