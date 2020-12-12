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

use Closure;
use DigitalOceanV2\Api\AbstractApi;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;
use Generator;
use ValueError;

final class ResultPager implements ResultPagerInterface
{
    /**
     * The default number of entries to request per page.
     *
     * @var int
     */
    private const PER_PAGE = 100;

    /**
     * The client to use for pagination.
     *
     * @var Client
     */
    private $client;

    /**
     * The number of entries to request per page.
     *
     * @var int
     */
    private $perPage;

    /**
     * The pagination result from the API.
     *
     * @var array<string,string>
     */
    private $pagination;

    /**
     * Create a new result pager instance.
     *
     * @param Client   $client
     * @param int|null $perPage
     *
     * @return void
     */
    public function __construct(Client $client, int $perPage = null)
    {
        if (null !== $perPage && ($perPage < 1 || $perPage > 200)) {
            throw new ValueError(\sprintf('%s::__construct(): Argument #2 ($perPage) must be between 1 and 200, or null', self::class));
        }

        $this->client = $client;
        $this->perPage = $perPage ?? self::PER_PAGE;
        $this->pagination = [];
    }

    /**
     * Fetch a single result from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws ExceptionInterface
     *
     * @return array
     */
    public function fetch(AbstractApi $api, string $method, array $parameters = []): array
    {
        $result = self::bindPerPage($api, $this->perPage)->$method(...$parameters);

        if (!\is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    /**
     * Fetch all results from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws ExceptionInterface
     *
     * @return array
     */
    public function fetchAll(AbstractApi $api, string $method, array $parameters = []): array
    {
        return \iterator_to_array($this->fetchAllLazy($api, $method, $parameters));
    }

    /**
     * Lazily fetch all results from an api call.
     *
     * @param AbstractApi $api
     * @param string      $method
     * @param array       $parameters
     *
     * @throws ExceptionInterface
     *
     * @return \Generator
     */
    public function fetchAllLazy(AbstractApi $api, string $method, array $parameters = []): Generator
    {
        $currentPage = 1;

        foreach ($this->fetch(self::bindPage($api, $currentPage), $method, $parameters) as $entry) {
            yield $entry;
        }

        while ($this->hasNext()) {
            foreach ($this->fetch(self::bindPage($api, ++$currentPage), $method, $parameters) as $entry) {
                yield $entry;
            }
        }
    }

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return isset($this->pagination['next']);
    }

    /**
     * Refresh the pagination property.
     *
     * @return void
     */
    private function postFetch(): void
    {
        $response = $this->client->getLastResponse();

        if (null === $response) {
            $this->pagination = [];
        } else {
            $this->pagination = ResponseMediator::getPagination($response);
        }
    }

    /**
     * @param AbstractApi $api
     * @param int         $page
     *
     * @return AbstractApi
     */
    private static function bindPage(AbstractApi $api, int $page): AbstractApi
    {
        $closure = Closure::bind(static function (AbstractApi $api) use ($page): AbstractApi {
            $clone = clone $api;

            $clone->page = $page;

            return $clone;
        }, null, AbstractApi::class);

        /** @var AbstractApi */
        return $closure($api);
    }

    /**
     * @param AbstractApi $api
     * @param int         $perPage
     *
     * @return AbstractApi
     */
    private static function bindPerPage(AbstractApi $api, int $perPage): AbstractApi
    {
        $closure = Closure::bind(static function (AbstractApi $api) use ($perPage): AbstractApi {
            $clone = clone $api;

            $clone->perPage = $perPage;

            return $clone;
        }, null, AbstractApi::class);

        /** @var AbstractApi */
        return $closure($api);
    }
}
