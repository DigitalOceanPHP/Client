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

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\ReservedIp as ReservedIpEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Manuel Christlieb <manuel@christlieb.eu>
 */
class ReservedIp extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return ReservedIpEntity[]
     */
    public function getAll(): array
    {
        $ips = $this->get('reserved_ips');

        return \array_map(function ($ip) {
            return new ReservedIpEntity($ip);
        }, $ips->reserved_ips);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(string $ipAddress): ReservedIpEntity
    {
        $ip = $this->get(\sprintf('reserved_ips/%s', $ipAddress));

        return new ReservedIpEntity($ip->reserved_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createAssigned(int $dropletId): ReservedIpEntity
    {
        $ip = $this->post('reserved_ips', ['droplet_id' => $dropletId]);

        return new ReservedIpEntity($ip->reserved_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createReserved(string $regionSlug): ReservedIpEntity
    {
        $ip = $this->post('reserved_ips', ['region' => $regionSlug]);

        return new ReservedIpEntity($ip->reserved_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $ipAddress): void
    {
        $this->delete(\sprintf('reserved_ips/%s', $ipAddress));
    }

    /**
     * @throws ExceptionInterface
     */
    public function getActions(string $ipAddress): array
    {
        $actions = $this->get(\sprintf('reserved_ips/%s/actions', $ipAddress));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getActionById(string $ipAddress, int $actionId): ActionEntity
    {
        $action = $this->get(\sprintf('reserved_ips/%s/actions/%d', $ipAddress, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @throws ExceptionInterface
     */
    public function assign(string $ipAddress, int $dropletId): ActionEntity
    {
        return $this->executeAction($ipAddress, ['type' => 'assign', 'droplet_id' => $dropletId]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function unassign(string $ipAddress): ActionEntity
    {
        return $this->executeAction($ipAddress, ['type' => 'unassign']);
    }

    /**
     * @throws ExceptionInterface
     */
    private function executeAction(string $ipAddress, array $options): ActionEntity
    {
        $action = $this->post(\sprintf('reserved_ips/%s/actions', $ipAddress), $options);

        return new ActionEntity($action->action);
    }
}
