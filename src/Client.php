<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Kirk <contact@sbin.dk>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2;

use DigitalOceanV2\Api\Account;
use DigitalOceanV2\Api\Action;
use DigitalOceanV2\Api\App;
use DigitalOceanV2\Api\CdnEndpoint;
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
use DigitalOceanV2\Api\Monitoring;
use DigitalOceanV2\Api\ProjectResource;
use DigitalOceanV2\Api\Region;
use DigitalOceanV2\Api\ReservedIp;
use DigitalOceanV2\Api\Size;
use DigitalOceanV2\Api\Snapshot;
use DigitalOceanV2\Api\Tag;
use DigitalOceanV2\Api\Volume;
use DigitalOceanV2\Api\Vpc;
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
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
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
    private const USER_AGENT = 'digitalocean-php-api-client/5.0';

    private readonly Builder $httpClientBuilder;
    private readonly History $responseHistory;

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

    public static function createWithHttpClient(ClientInterface $httpClient): self
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    public function account(): Account
    {
        return new Account($this);
    }

    public function action(): Action
    {
        return new Action($this);
    }

    public function app(): App
    {
        return new App($this);
    }

    public function cdnEndpoint(): CdnEndpoint
    {
        return new CdnEndpoint($this);
    }

    public function certificate(): Certificate
    {
        return new Certificate($this);
    }

    public function database(): Database
    {
        return new Database($this);
    }

    public function domain(): Domain
    {
        return new Domain($this);
    }

    public function domainRecord(): DomainRecord
    {
        return new DomainRecord($this);
    }

    public function droplet(): Droplet
    {
        return new Droplet($this);
    }

    public function firewall(): Firewall
    {
        return new Firewall($this);
    }

    public function floatingIp(): FloatingIp
    {
        return new FloatingIp($this);
    }

    public function image(): Image
    {
        return new Image($this);
    }

    public function key(): Key
    {
        return new Key($this);
    }

    public function loadBalancer(): LoadBalancer
    {
        return new LoadBalancer($this);
    }

    public function monitoring(): Monitoring
    {
        return new Monitoring($this);
    }

    public function projectResource(): ProjectResource
    {
        return new ProjectResource($this);
    }

    public function region(): Region
    {
        return new Region($this);
    }

    public function reservedIp(): ReservedIp
    {
        return new ReservedIp($this);
    }

    public function size(): Size
    {
        return new Size($this);
    }

    public function snapshot(): Snapshot
    {
        return new Snapshot($this);
    }

    public function tag(): Tag
    {
        return new Tag($this);
    }

    public function volume(): Volume
    {
        return new Volume($this);
    }

    public function vpc(): Vpc
    {
        return new Vpc($this);
    }

    public function authenticate(string $token): void
    {
        $this->getHttpClientBuilder()->addPlugin(new Authentication($token));
    }

    /**
     * Set the base URL.
     */
    public function setUrl(string $url): void
    {
        $this->httpClientBuilder->removePlugin(AddHostPlugin::class);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri($url)));
    }

    /**
     * Get the last response.
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * Get the HTTP client.
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Get the HTTP client builder.
     */
    protected function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }
}
