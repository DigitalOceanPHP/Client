<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

use DigitalOceanV2\Adapter\AdapterInterface;
use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\RateLimit;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Volume;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class DigitalOceanV2
{
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
     * @return Account
     */
    public function account()
    {
        return new Account($this->adapter);
    }

    /**
     * @return Action
     */
    public function action()
    {
        return new Action($this->adapter);
    }

    /**
     * @return Certificate
     */
    public function certificate()
    {
        return new Certificate($this->adapter);
    }

    /**
     * @return Domain
     */
    public function domain()
    {
        return new Domain($this->adapter);
    }

    /**
     * @return DomainRecord
     */
    public function domainRecord()
    {
        return new DomainRecord($this->adapter);
    }

    /**
     * @return Droplet
     */
    public function droplet()
    {
        return new Droplet($this->adapter);
    }

    /**
     * @return FloatingIp
     */
    public function floatingIp()
    {
        return new FloatingIp($this->adapter);
    }

    /**
     * @return Image
     */
    public function image()
    {
        return new Image($this->adapter);
    }

    /**
     * @return Key
     */
    public function key()
    {
        return new Key($this->adapter);
    }

    /**
     * @return RateLimit
     */
    public function rateLimit()
    {
        return new RateLimit($this->adapter);
    }

    /**
     * @return Region
     */
    public function region()
    {
        return new Region($this->adapter);
    }

    /**
     * @return Size
     */
    public function size()
    {
        return new Size($this->adapter);
    }

    /**
     * @return Volume
     */
    public function volume()
    {
        return new Volume($this->adapter);
    }

    /**
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this->adapter);
    }
}
