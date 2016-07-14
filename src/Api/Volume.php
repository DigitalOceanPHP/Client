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

use DigitalOceanV2\Entity\Volume as VolumeEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Volume extends AbstractApi
{
    /**
     * @return VolumeEntity[]
     */
    public function getAll()
    {
        $volumes = $this->adapter->get(sprintf('%s/volumes?per_page=%d', $this->endpoint, 200));

        $volumes = json_decode($volumes);

        $this->extractMeta($volumes);

        return array_map(function ($volume) {
            return new VolumeEntity($volume);
        }, $volumes->volumes);
    }
}
