<?php

declare(strict_types=1);

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Client;
use DigitalOceanV2\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class IntegrationTest extends TestCase
{
    public function testWithoutAuth()
    {
        $client = new Client();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to authenticate you');

        $client->account()->getUserInformation();
    }
}
