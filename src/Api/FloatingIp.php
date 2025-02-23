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
use DigitalOceanV2\Entity\FloatingIp as FloatingIpEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class FloatingIp extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity[]
     */
    public function getAll(): array
    {
        $ips = $this->get('floating_ips');

        return \array_map(function ($ip) {
            return new FloatingIpEntity($ip);
        }, $ips->floating_ips);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(string $ipAddress): FloatingIpEntity
    {
        $ip = $this->get(\sprintf('floating_ips/%s', $ipAddress));

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createAssigned(int $dropletId): FloatingIpEntity
    {
        $ip = $this->post('floating_ips', ['droplet_id' => $dropletId]);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createReserved(string $regionSlug): FloatingIpEntity
    {
        $ip = $this->post('floating_ips', ['region' => $regionSlug]);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $ipAddress): void
    {
        $this->delete(\sprintf('floating_ips/%s', $ipAddress));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions(string $ipAddress): array
    {
        $actions = $this->get(\sprintf('floating_ips/%s/actions', $ipAddress));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getActionById(string $ipAddress, int $actionId): ActionEntity
    {
        $action = $this->get(\sprintf('floating_ips/%s/actions/%d', $ipAddress, $actionId));

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
        $action = $this->post(\sprintf('floating_ips/%s/actions', $ipAddress), $options);

        return new ActionEntity($action->action);
    }
}
