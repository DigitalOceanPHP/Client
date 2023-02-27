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

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\CdnEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * @author Christian Fuentes <christian@topworksheets.com>
 */
class CdnEndpointEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $endpoint = new CdnEndpoint([
            'id' => '8d077fd4-e67e-409c-b927-aa92dfaf0e69',
            'origin' => 'fake-cdn.nyc3.digitaloceanspaces.com',
            'endpoint' => 'fake-cdn.nyc3.cdn.digitaloceanspaces.com',
            'created_at' => '2018-07-19T15:04:16Z',
            'ttl' => 1800,
            'certificate_id' => '892071a0-bb95-49bc-8021-3afd67a210bf',
            'custom_domain' => 'fake-cdn.example.com',
        ]);

        self::assertInstanceOf(AbstractEntity::class, $endpoint);
        self::assertInstanceOf(CdnEndpoint::class, $endpoint);
        self::assertSame('8d077fd4-e67e-409c-b927-aa92dfaf0e69', $endpoint->id);
        self::assertSame('fake-cdn.nyc3.digitaloceanspaces.com', $endpoint->origin);
        self::assertSame('fake-cdn.nyc3.cdn.digitaloceanspaces.com', $endpoint->endpoint);
        self::assertSame('2018-07-19T15:04:16Z', $endpoint->createdAt);
        self::assertSame(1800, $endpoint->ttl);
        self::assertSame('892071a0-bb95-49bc-8021-3afd67a210bf', $endpoint->certificateId);
        self::assertSame('fake-cdn.example.com', $endpoint->customDomain);
    }
}
