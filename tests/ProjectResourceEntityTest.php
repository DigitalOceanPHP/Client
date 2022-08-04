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
use DigitalOceanV2\Entity\ProjectResource;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Mohammad Salamat <godfather@mofia.org>
 */
class ProjectResourceEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $projectResource = new ProjectResource([
            'urn' => 'do:droplet:123456789',
            'assignedAt' => '2022-08-04T04:26:24Z',
            'links' => [
                'self' => 'https://api.digitalocean.com/v2/droplets/123456789'
            ],
            'status' => 'already_assigned'
        ]);

        $this->assertInstanceOf(AbstractEntity::class, $projectResource);
        $this->assertInstanceOf(ProjectResource::class, $projectResource);
        $this->assertSame('do:droplet:123456789', $projectResource->urn);
        $this->assertSame('2022-08-04T04:26:24Z', $projectResource->assignedAt);
        $this->assertSame(['self' => 'https://api.digitalocean.com/v2/droplets/123456789'], $projectResource->links);
        $this->assertSame('already_assigned', $projectResource->status);
    }
}
