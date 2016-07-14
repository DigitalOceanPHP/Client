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
     * @param string $regionSlug restricts results to volumes available in a specific region.
     * @return VolumeEntity[] Lists all of the Block Storage volumes available.
     */
    public function getAll($regionSlug = NULL)
    {
        $regionQueryParameter = is_null($regionSlug) ? "" : sprintf("&region=%s", $regionSlug);
        $volumes = $this->adapter->get(sprintf('%s/volumes?per_page=%d%s', $this->endpoint, 200, $regionQueryParameter));

        $volumes = json_decode($volumes);

        $this->extractMeta($volumes);

        return array_map(function ($volume) {
            return new VolumeEntity($volume);
        }, $volumes->volumes);
    }

    /**
     * @param string $driveName restricts results to volumes with the specified name.
     * @param string $regionSlug restricts results to volumes available in a specific region.
     * @return VolumeEntity[] Lists all of the Block Storage volumes available.
     */
    public function getByNameAndRegion($driveName, $regionSlug)
    {
        $volumes = $this->adapter->get(sprintf('%s/volumes?per_page=%d&region=%s&name=%s', $this->endpoint, 200, $regionSlug, $driveName));

        $volumes = json_decode($volumes);

        $this->extractMeta($volumes);

        return array_map(function ($volume) {
            return new VolumeEntity($volume);
        }, $volumes->volumes);
    }

    /**
     * @param string $id
     * @return VolumeEntity the Block Storage volume with the specified id.
     */
    public function getById($id)
    {
        $volume = $this->adapter->get(sprintf('%s/volumes/%s?per_page=%d', $this->endpoint, $id, 200));

        $volume = json_decode($volume);

        return new VolumeEntity($volume->volume);
    }
}
