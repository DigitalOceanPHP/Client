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

use DigitalOceanV2\Entity\Vpc;
use PHPUnit\Framework\TestCase;

/**
 * @author Manuel Christlieb <manuel@gchristlieb.eu>
 */
class VpcTest extends TestCase
{
    public function testConstructor(): void
    {
        $values = [
            'name' => 'env.prod-vpc',
            'description' => 'VPC for production environment',
            'region' => 'nyc1',
            'ip_range' => '10.10.10.0/24',
            'id' => '5a4981aa-9653-4bd1-bef5-d6bff52042e4',
            'urn' => 'do:vpc:5a4981aa-9653-4bd1-bef5-d6bff52042e4',
            'default' => false,
            'created_at' => '2020-03-13T19:20:47.442049222Z',
        ];

        $entity = new Vpc($values);

        self::assertEquals($values['name'], $entity->name);
        self::assertEquals($values['description'], $entity->description);
        self::assertEquals($values['region'], $entity->region);
        self::assertEquals($values['ip_range'], $entity->ipRange);
        self::assertEquals($values['id'], $entity->id);
        self::assertEquals($values['urn'], $entity->urn);
        self::assertEquals($values['default'], $entity->default);
        self::assertEquals($values['created_at'], $entity->createdAt);
    }
}
