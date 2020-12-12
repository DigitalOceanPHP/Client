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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Region as RegionEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Region extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return RegionEntity[]
     */
    public function getAll()
    {
        $regions = $this->get('regions');

        return \array_map(function ($region) {
            return new RegionEntity($region);
        }, $regions->regions);
    }
}
