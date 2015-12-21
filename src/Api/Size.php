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

use DigitalOceanV2\Entity\Size as SizeEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Size extends AbstractApi
{
    /**
     * @return SizeEntity[]
     */
    public function getAll()
    {
        $sizes = $this->adapter->get(sprintf('%s/sizes?per_page=%d', $this->endpoint, 200));

        $sizes = json_decode($sizes);

        $this->extractMeta($sizes);

        return array_map(function ($size) {
            return new SizeEntity($size);
        }, $sizes->sizes);
    }
}
