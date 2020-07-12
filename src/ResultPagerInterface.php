<?php

declare(strict_types=1);

namespace DigitalOceanV2;

use DigitalOceanV2\Api\ApiInterface;
use DigitalOceanV2\Exception\ExceptionInterface;

interface ResultPagerInterface
{
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
    public function fetch(ApiInterface $api, string $method, array $parameters = []);

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
    public function fetchAll(ApiInterface $api, string $method, array $parameters = []);

    /**
     * Check to determine the availability of a next page.
     *
     * @return bool
     */
    public function hasNext();

    /**
     * Check to determine the availability of a previous page.
     *
     * @return bool
     */
    public function hasPrevious();
}
