<?php
/**
 * Created by PhpStorm.
 * User: Yassir
 * Date: 30/06/14
 * Time: 23:31
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\RateLimit as RateLimitEntity;

class RateLimit extends AbstractApi
{

    /**
     * @return RateLimitEntity|null
     */
    public function getRateLimit()
    {
        $results = $this->adapter->getLatestResponseHeaders();
        return $results != null ? new RateLimitEntity($results) : null;
    }
}
