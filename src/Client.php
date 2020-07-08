<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

use DigitalOceanV2\HttpClient\Builder;
use DigitalOceanV2\HttpClient\FactoryInterface;
use DigitalOceanV2\HttpClient\HttpClientInterface;
use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\LoadBalancer;
use DigitalOceanV2\Api\RateLimit;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Client
{
    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @param Builder|null $httpClientBuilder
     *
     * @return void
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?? new Builder();
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return Client
     */
    public static function createWithFactory(FactoryInterface $factory)
    {
        $builder = new Builder($factory);

        return new self($builder);
    }

    /**
     * @return Account
     */
    public function account()
    {
        return new Account($this->getHttpClient());
    }

    /**
     * @return Action
     */
    public function action()
    {
        return new Action($this->getHttpClient());
    }

    /**
     * @return Certificate
     */
    public function certificate()
    {
        return new Certificate($this->getHttpClient());
    }

    /**
     * @return Domain
     */
    public function domain()
    {
        return new Domain($this->getHttpClient());
    }

    /**
     * @return DomainRecord
     */
    public function domainRecord()
    {
        return new DomainRecord($this->getHttpClient());
    }

    /**
     * @return Droplet
     */
    public function droplet()
    {
        return new Droplet($this->getHttpClient());
    }

    /**
     * @return FloatingIp
     */
    public function floatingIp()
    {
        return new FloatingIp($this->getHttpClient());
    }

    /**
     * @return Image
     */
    public function image()
    {
        return new Image($this->getHttpClient());
    }

    /**
     * @return Key
     */
    public function key()
    {
        return new Key($this->getHttpClient());
    }

    /**
     * @return LoadBalancer
     */
    public function loadBalancer()
    {
        return new LoadBalancer($this->getHttpClient());
    }

    /**
     * @return RateLimit
     */
    public function rateLimit()
    {
        return new RateLimit($this->getHttpClient());
    }

    /**
     * @return Region
     */
    public function region()
    {
        return new Region($this->getHttpClient());
    }

    /**
     * @return Size
     */
    public function size()
    {
        return new Size($this->getHttpClient());
    }

    /**
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this->getHttpClient());
    }

    /**
     * @return Tag
     */
    public function tag()
    {
        return new Tag($this->getHttpClient());
    }

    /**
     * @return Volume
     */
    public function volume()
    {
        return new Volume($this->getHttpClient());
    }

    /**
     * @param string|null $token
     *
     * @return void
     */
    public function authenticate(string $token = null)
    {
        $this->getHttpClientBuilder()->setAuthToken($token);
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
