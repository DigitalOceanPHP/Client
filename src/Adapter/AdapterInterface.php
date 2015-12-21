<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Adapter;

use DigitalOceanV2\Exception\HttpException;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
interface AdapterInterface
{
    /**
     * @param string $url
     *
     * @throws HttpException
     *
     * @return string
     */
    public function get($url);

    /**
     * @param string $url
     *
     * @throws HttpException
     */
    public function delete($url);

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function put($url, $content = '');

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function post($url, $content = '');

    /**
     * @return array|null
     */
    public function getLatestResponseHeaders();
}
