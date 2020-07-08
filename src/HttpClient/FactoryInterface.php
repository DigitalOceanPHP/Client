<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\HttpClient;

use DigitalOceanV2\Exception\HttpException;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
interface FactoryInterface
{
    /**
     * @param string|null $token
     *
     * @return HttpClientInterface
     */
    public function create(string $token = null);
}
