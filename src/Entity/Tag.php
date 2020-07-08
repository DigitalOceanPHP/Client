<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Nicolas Beauvais <nicolas@bvs.email>
 */
final class Tag extends AbstractEntity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $resources;
}
