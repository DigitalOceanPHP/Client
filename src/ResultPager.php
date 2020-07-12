<?php

declare(strict_types=1);

namespace DigitalOceanV2;

use DigitalOceanV2\Api\ApiInterface;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\RuntimeException;
use DigitalOceanV2\HttpClient\Message\ResponseMediator;

final class ResultPager implements ResultPagerInterface
{
    /**
     * The default number of entries to request per page.
     *
     * @var int
     */
    private const PER_PAGE = 200;

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
        $this->client = $client;
        $this->perPage = $perPage ?? self::PER_PAGE;
        $this->pagination = [];
    }

    /**
     * Fetch a single result from an api call.
     *
     * @param ApiInterface $api
     * @param string       $method
     * @param array        $parameters
     *
     * @throws ExceptionInterface
     *
     * @return array
     */
    public function fetch(ApiInterface $api, string $method, array $parameters = [])
    {
        $result = $api->perPage($this->perPage)->$method(...$parameters);

        if (!is_array($result)) {
            throw new RuntimeException('Pagination of this endpoint is not supported.');
        }

        $this->postFetch();

        return $result;
    }

    /**
     * Fetch all results from an api call.
     *
     * @param ApiInterface $api
     * @param string       $method
     * @param array        $parameters
     *
     * @throws ExceptionInterface
     *
     * @return array
     */
    public function fetchAll(ApiInterface $api, string $method, array $parameters = [])
    {
        return iterator_to_array($this->fetchAllLazy($api, $method, $parameters));
    }

    /**
     * Lazily fetch all results from an api call.
     *
     * @param ApiInterface $api
     * @param string       $method
     * @param array        $parameters
     *
     * @throws ExceptionInterface
     *
     * @return \Generator
     */
    public function fetchAllLazy(ApiInterface $api, string $method, array $parameters = [])
    {
        $currentPage = 1;

        foreach ($this->fetch($api->page($currentPage), $method, $parameters) as $entry) {
            yield $entry;
        }

        while ($this->hasNext()) {
            foreach ($this->fetch($api->page(++$currentPage), $method, $parameters) as $entry) {
                yield $entry;
            }
        }
    }

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext()
    {
        return isset($this->pagination['next']);
    }

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return isset($this->pagination['prev']);
    }

    /**
     * Refresh the pagination property.
     *
     * @return void
     */
    private function postFetch()
    {
        $response = $this->client->getLastResponse();

        if (null === $response) {
            $this->pagination = [];
        } else {
            $this->pagination = ResponseMediator::getPagination($response);
        }
    }
}
