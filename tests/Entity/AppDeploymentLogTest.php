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

namespace DigitalOceanV2\Tests\Entity;

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\AppDeploymentLog;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AppDeploymentLogTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'liveUrl' => 'liveUrl',
            'historicUrls' => [],
        ];

        $entity = new AppDeploymentLog($values);

        $this->assertInstanceOf(AbstractEntity::class, $entity);
        $this->assertInstanceOf(AppDeploymentLog::class, $entity);
        $this->assertSame($values['liveUrl'], $entity->liveUrl);
        $this->assertSame($values['historicUrls'], $entity->historicUrls);

        $this->assertSame($values['liveUrl'], $entity->live_url);
        $this->assertSame($values['historicUrls'], $entity->historic_urls);
    }
}
