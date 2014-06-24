<?php

/**
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Region as RegionEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Region extends AbstractApi
{
    /**
     * @return RegionEntity[]
     */
    public function getAll()
    {
        $regions = $this->adapter->get(sprintf("%s/regions", self::ENDPOINT));
        $regions = json_decode($regions);

        $results = array();
        foreach ($regions->regions as $region) {
            $results[] = new RegionEntity($region);
        }

        return $results;
    }
}