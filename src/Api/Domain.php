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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Domain as DomainEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Domain extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return DomainEntity[]
     */
    public function getAll()
    {
        $domains = $this->get('domains');

        return \array_map(function ($domain) {
            return new DomainEntity($domain);
        }, $domains->domains);
    }

    /**
     * @param string $domainName
     *
     * @throws ExceptionInterface
     *
     * @return DomainEntity
     */
    public function getByName(string $domainName)
    {
        $domain = $this->get(\sprintf('domains/%s', $domainName));

        return new DomainEntity($domain->domain);
    }

    /**
     * @param string      $name
     * @param string|null $ipAddress
     *
     * @throws ExceptionInterface
     *
     * @return DomainEntity
     */
    public function create(string $name, ?string $ipAddress = null)
    {
        $data = [
            'name' => $name,
        ];

        if (null !== $ipAddress) {
            $data['ip_address'] = $ipAddress;
        }

        $domain = $this->post('domains', $data);

        return new DomainEntity($domain->domain);
    }

    /**
     * @param string $domain
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $domain): void
    {
        $this->delete(\sprintf('domains/%s', $domain));
    }
}
