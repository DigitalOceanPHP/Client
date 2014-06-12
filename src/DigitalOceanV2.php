<?php

/**
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Adapter\AdapterInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DigitalOceanV2
{
    /**
     * @see http://semver.org/
     */
    const VERSION = '0.1-dev';

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
     * @return Action
     */
    public function action()
    {
        return new Action($this->adapter);
    }
}
