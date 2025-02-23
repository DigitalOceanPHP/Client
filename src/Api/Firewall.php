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

use DigitalOceanV2\Entity\Firewall as FirewallEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Firewall extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return FirewallEntity[]
     */
    public function getAll(): array
    {
        $firewalls = $this->get('firewalls');

        return \array_map(function ($firewall) {
            return new FirewallEntity($firewall);
        }, $firewalls->firewalls);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(string $id): FirewallEntity
    {
        $firewall = $this->get(\sprintf('firewalls/%s', $id));

        return new FirewallEntity($firewall->firewall);
    }

    public function create(string $name, array $inboundRules, array $outboundRules, array $dropletIds = [], array $tags = []): FirewallEntity
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
     * @throws ExceptionInterface
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('firewalls/%s', $id));
    }

    /**
     * @throws ExceptionInterface
     */
    public function update(string $id, FirewallEntity $firewall): FirewallEntity
    {
        $data = $firewall->toArray();

        $result = $this->put(\sprintf('firewalls/%s', $id), $data);

        return new FirewallEntity($result->firewall);
    }

    /**
     * @throws ExceptionInterface
     */
    public function addRules(string $id, array $rules): void
    {
        $this->post(\sprintf('firewalls/%s/rules', $id), $rules);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeRules(string $id, array $rules): void
    {
        $this->delete(\sprintf('firewalls/%s/rules', $id), $rules);
    }

    /**
     * @throws ExceptionInterface
     */
    public function addDroplets(string $id, array $droplets): void
    {
        $this->post(\sprintf('firewalls/%s/droplets', $id), $droplets);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeDroplets(string $id, array $droplets): void
    {
        $this->delete(\sprintf('firewalls/%s/droplets', $id), $droplets);
    }

    /**
     * @throws ExceptionInterface
     */
    public function addTags(string $id, array $tags): void
    {
        $firewalls = $this->post(\sprintf('firewalls/%s/tags', $id), $tags);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeTags(string $id, array $tags): void
    {
        $this->delete(\sprintf('firewalls/%s/tags', $id), $tags);
    }
}
