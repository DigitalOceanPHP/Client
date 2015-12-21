<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\RateLimit as RateLimitEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class RateLimit extends AbstractApi
{
    /**
     * @return RateLimitEntity|null
     */
    public function getRateLimit()
    {
        if ($results = $this->adapter->getLatestResponseHeaders()) {
            return new RateLimitEntity($results);
        }
    }
}
