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
     * @param string $name
     * @param array  $inboundRules
     * @param array  $outboundRules
     * @param array  $dropletIds
     * @param array  $tags
     *
     * @return FirewallEntity
     */
    public function create(string $name, array $inboundRules, array $outboundRules, array $dropletIds = [], array $tags = [])
    {
        $data = [
            'name' => $name,
            'inbound_rules' => $inboundRules,
            'outbound_rules' => $outboundRules,
        ];

        if (0 < \count($dropletIds)) {
            $data['droplet_ids'] = $dropletIds;
        }

        if (0 < \count($tags)) {
            $data['tags'] = $tags;
        }

        $firewall = $this->post('firewalls', $data);

        return new FirewallEntity($firewall->firewall);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('firewalls/%s', $id));
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

    /**
     * @param string $id
     * @param array  $rules
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function addRules(string $id, array $rules): void
    {
        $this->post(\sprintf('firewalls/%s/rules', $id), $rules);
    }

    /**
     * @param string $id
     * @param array  $rules
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeRules(string $id, array $rules): void
    {
        $this->delete(\sprintf('firewalls/%s/rules', $id), $rules);
    }

    /**
     * @param string $id
     * @param array  $droplets
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function addDroplets(string $id, array $droplets): void
    {
        $this->post(\sprintf('firewalls/%s/droplets', $id), $droplets);
    }

    /**
     * @param string $id
     * @param array  $droplets
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeDroplets(string $id, array $droplets): void
    {
        $this->delete(\sprintf('firewalls/%s/droplets', $id), $droplets);
    }

    /**
     * @param string $id
     * @param array  $tags
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function addTags(string $id, array $tags): void
    {
        $firewalls = $this->post(\sprintf('firewalls/%s/tags', $id), $tags);
    }

    /**
     * @param string $id
     * @param array  $tags
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeTags(string $id, array $tags): void
    {
        $this->delete(\sprintf('firewalls/%s/tags', $id), $tags);
    }
}
