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

use DigitalOceanV2\Entity\Size as SizeEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Size extends AbstractApi
{
    /**
     * @return SizeEntity[]
     */
    public function getAll()
    {
        $sizes = $this->adapter->get(sprintf("%s/sizes", self::ENDPOINT));
        $sizes = json_decode($sizes);

        $results = array();
        foreach ($sizes->sizes as $size) {
            $results[] = new SizeEntity($size);
        }

        return $results;
    }
}
