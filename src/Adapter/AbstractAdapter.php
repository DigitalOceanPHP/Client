<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Adapter;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
abstract class AbstractAdapter
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
