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

use DigitalOceanV2\Entity\Region as RegionEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Region extends AbstractApi
{
    /**
     * @return RegionEntity[]
     */
    public function getAll()
    {
        $regions = $this->adapter->get(sprintf('%s/regions?per_page=%d', $this->endpoint, 200));

        $regions = json_decode($regions);

        $this->extractMeta($regions);

        return array_map(function ($region) {
            return new RegionEntity($region);
        }, $regions->regions);
    }
}
