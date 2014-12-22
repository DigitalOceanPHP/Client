<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Domain as DomainEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Domain extends AbstractApi
{
    /**
     * @return DomainEntity[]
     */
    public function getAll()
    {
        $domains = $this->adapter->get(sprintf('%s/domains?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
        $domains = json_decode($domains);

        $this->extractMeta($domains);

        return array_map(function ($domain) {
            return new DomainEntity($domain);
        }, $domains->domains);
    }

    /**
     * @param string $domainName
     *
     * @throws \RuntimeException
     *
     * @return DomainEntity
     */
    public function getByName($domainName)
    {
        $domain = $this->adapter->get(sprintf('%s/domains/%s', self::ENDPOINT, $domainName));
        $domain = json_decode($domain);

        return new DomainEntity($domain->domain);
    }

    /**
     * @param string $name
     * @param string $ipAddress
     *
     * @throws \RuntimeException
     *
     * @return DomainEntity
     */
    public function create($name, $ipAddress)
    {
        $headers = array('Content-Type: application/json');
        $content = sprintf('{"name":"%s", "ip_address":"%s"}', $name, $ipAddress);

        $domain = $this->adapter->post(sprintf('%s/domains', self::ENDPOINT), $headers, $content);
        $domain = json_decode($domain);

        return new DomainEntity($domain->domain);
    }

    /**
     * @param string $domain
     *
     * @throws \RuntimeException
     */
    public function delete($domain)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/domains/%s', self::ENDPOINT, $domain), $headers);
    }
}
