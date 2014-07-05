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

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Entity\Meta;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
abstract class AbstractApi
{
    /**
     * API v2
     */
    const ENDPOINT = 'https://api.digitalocean.com/v2';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param  string    $data
     * @return Meta|null
     */
    protected function getMeta($data)
    {
        if (!isset($data->meta)) {
            return null;
        }

        return new Meta($data->meta);
    }
}
