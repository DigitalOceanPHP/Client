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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Firewall as FirewallEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Firewall extends AbstractApi
{
    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return FirewallEntity
     */
    public function getById(string $id)
    {
        $firewall = $this->get(\sprintf('firewalls/%s', $id));

        return new FirewallEntity($firewall->firewall);
    }

    /**
     * @param string         $id
     * @param FirewallEntity $firewall
     *
     * @throws ExceptionInterface
     *
     * @return FirewallEntity
     */
    public function update(string $id, $firewall)
    {
        $data = $firewall->toArray();

        $result = $this->put(\sprintf('firewalls/%s', $id), $data);

        return new FirewallEntity($result->firewall);
    }
}
