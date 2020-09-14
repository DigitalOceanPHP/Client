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

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Database;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\FloatingIp;
use DigitalOceanV2\Api\Image;
use DigitalOceanV2\Api\Key;
use DigitalOceanV2\Api\LoadBalancer;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;
use DigitalOceanV2\HttpClient\Builder;
use DigitalOceanV2\HttpClient\HttpClientInterface;
use DigitalOceanV2\HttpClient\HttpMethodsClientInterface;
use DigitalOceanV2\HttpClient\Message\Response;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Client
{
    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://api.digitalocean.com';

    /**
     * The default user agent header.
     *
     * @var string
     */
    private const USER_AGENT = 'digitalocean-php-api-client/3.0';

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

        $this->httpClientBuilder->setUserAgent(self::USER_AGENT);

        $this->setUrl(self::BASE_URL);
    }

    /**
     * @param HttpClientInterface $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClientInterface $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @return Account
     */
    public function account()
    {
        return new Account($this);
    }

    /**
     * @return Action
     */
    public function action()
    {
        return new Action($this);
    }

    /**
     * @return Certificate
     */
    public function certificate()
    {
        return new Certificate($this);
    }

    /**
     * @return Database
     */
    public function database()
    {
        return new Database($this);
    }

    /**
     * @return Domain
     */
    public function domain()
    {
        return new Domain($this);
    }

    /**
     * @return DomainRecord
     */
    public function domainRecord()
    {
        return new DomainRecord($this);
    }

    /**
     * @return Droplet
     */
    public function droplet()
    {
        return new Droplet($this);
    }

    /**
     * @return FloatingIp
     */
    public function floatingIp()
    {
        return new FloatingIp($this);
    }

    /**
     * @return Image
     */
    public function image()
    {
        return new Image($this);
    }

    /**
     * @return Key
     */
    public function key()
    {
        return new Key($this);
    }

    /**
     * @return LoadBalancer
     */
    public function loadBalancer()
    {
        return new LoadBalancer($this);
    }

    /**
     * @return Region
     */
    public function region()
    {
        return new Region($this);
    }

    /**
     * @return Size
     */
    public function size()
    {
        return new Size($this);
    }

    /**
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this);
    }

    /**
     * @return Tag
     */
    public function tag()
    {
        return new Tag($this);
    }

    /**
     * @return Volume
     */
    public function volume()
    {
        return new Volume($this);
    }

    /**
     * @param string $token
     *
     * @return void
     */
    public function authenticate(string $token)
    {
        $this->getHttpClientBuilder()->setAuthToken($token);
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->getHttpClientBuilder()->setBaseUrl($url);
    }

    /**
     * @return Response|null
     */
    public function getLastResponse()
    {
        return $this->getHttpClient()->getLastResponse();
    }

    /**
     * @return HttpMethodsClientInterface
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
