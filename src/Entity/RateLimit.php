<?php

/**
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class RateLimit extends AbstractEntity
{
    /**
     * @var integer
     */
    public $limit;

    /**
     * @var integer
     */
    public $remaining;

    /**
     * @var integer
     */
    public $reset;
}
