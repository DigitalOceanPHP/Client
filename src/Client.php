<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\App;
use DigitalOceanV2\Api\Certificate;
use DigitalOceanV2\Api\Database;
use DigitalOceanV2\Api\Domain;
use DigitalOceanV2\Api\DomainRecord;
use DigitalOceanV2\Api\Droplet;
use DigitalOceanV2\Api\Firewall;
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
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use DigitalOceanV2\HttpClient\Plugin\Authentication;
use DigitalOceanV2\HttpClient\Plugin\ExceptionThrower;
use DigitalOceanV2\HttpClient\Plugin\History;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

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
    private const USER_AGENT = 'digitalocean-php-api-client/4.3';

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * @param Builder|null $httpClientBuilder
     *
     * @return void
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
        $this->responseHistory = new History();

        $builder->addPlugin(new ExceptionThrower());
        $builder->addPlugin(new HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new RedirectPlugin());

        $builder->addPlugin(new HeaderDefaultsPlugin([
            'Accept' => ResponseMediator::JSON_CONTENT_TYPE,
            'User-Agent' => self::USER_AGENT,
        ]));

        $this->setUrl(self::BASE_URL);
    }

    /**
     * @param ClientInterface $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(ClientInterface $httpClient): self
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @return Account
     */
    public function account(): Account
    {
        return new Account($this);
    }

    /**
     * @return Action
     */
    public function action(): Action
    {
        return new Action($this);
    }

    /**
     * @return App
     */
    public function app(): App
    {
        return new App($this);
    }

    /**
     * @return Certificate
     */
    public function certificate(): Certificate
    {
        return new Certificate($this);
    }

    /**
     * @return Database
     */
    public function database(): Database
    {
        return new Database($this);
    }

    /**
     * @return Domain
     */
    public function domain(): Domain
    {
        return new Domain($this);
    }

    /**
     * @return DomainRecord
     */
    public function domainRecord(): DomainRecord
    {
        return new DomainRecord($this);
    }

    /**
     * @return Droplet
     */
    public function droplet(): Droplet
    {
        return new Droplet($this);
    }

    /**
     * @return Firewall
     */
    public function firewall(): Firewall
    {
        return new Firewall($this);
    }

    /**
     * @return FloatingIp
     */
    public function floatingIp(): FloatingIp
    {
        return new FloatingIp($this);
    }

    /**
     * @return Image
     */
    public function image(): Image
    {
        return new Image($this);
    }

    /**
     * @return Key
     */
    public function key(): Key
    {
        return new Key($this);
    }

    /**
     * @return LoadBalancer
     */
    public function loadBalancer(): LoadBalancer
    {
        return new LoadBalancer($this);
    }

    /**
     * @return Region
     */
    public function region(): Region
    {
        return new Region($this);
    }

    /**
     * @return Size
     */
    public function size(): Size
    {
        return new Size($this);
    }

    /**
     * @return Snapshot
     */
    public function snapshot(): Snapshot
    {
        return new Snapshot($this);
    }

    /**
     * @return Tag
     */
    public function tag(): Tag
    {
        return new Tag($this);
    }

    /**
     * @return Volume
     */
    public function volume(): Volume
    {
        return new Volume($this);
    }

    /**
     * @param string $token
     *
     * @return void
     */
    public function authenticate(string $token): void
    {
        $this->getHttpClientBuilder()->addPlugin(new Authentication($token));
    }

    /**
     * Set the base URL.
     *
     * @param string $url
     *
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->httpClientBuilder->removePlugin(AddHostPlugin::class);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($url)));
    }

    /**
     * Get the last response.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * Get the HTTP client.
     *
     * @return \Http\Client\Common\HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Get the HTTP client builder.
     *
     * @return \DigitalOceanV2\HttpClient\Builder
     */
    protected function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }
}
