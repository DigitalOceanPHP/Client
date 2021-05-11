<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
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

        $this->assertInstanceOf(AbstractEntity::class, $endpoint);
        $this->assertInstanceOf(CdnEndpoint::class, $endpoint);
        $this->assertSame('8d077fd4-e67e-409c-b927-aa92dfaf0e69', $endpoint->id);
        $this->assertSame('fake-cdn.nyc3.digitaloceanspaces.com', $endpoint->origin);
        $this->assertSame('fake-cdn.nyc3.cdn.digitaloceanspaces.com', $endpoint->endpoint);
        $this->assertSame('2018-07-19T15:04:16Z', $endpoint->createdAt);
        $this->assertSame(1800, $endpoint->ttl);
        $this->assertSame('892071a0-bb95-49bc-8021-3afd67a210bf', $endpoint->certificateId);
        $this->assertSame('fake-cdn.example.com', $endpoint->customDomain);
    }
}
