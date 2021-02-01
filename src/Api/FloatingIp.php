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

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\FloatingIp as FloatingIpEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class FloatingIp extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity[]
     */
    public function getAll()
    {
        $ips = $this->get('floating_ips');

        return \array_map(function ($ip) {
            return new FloatingIpEntity($ip);
        }, $ips->floating_ips);
    }

    /**
     * @param string $ipAddress
     *
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity
     */
    public function getById(string $ipAddress)
    {
        $ip = $this->get(\sprintf('floating_ips/%s', $ipAddress));

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param int $dropletId
     *
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity
     */
    public function createAssigned(int $dropletId)
    {
        $ip = $this->post('floating_ips', ['droplet_id' => $dropletId]);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param string $regionSlug
     *
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity
     */
    public function createReserved(string $regionSlug)
    {
        $ip = $this->post('floating_ips', ['region' => $regionSlug]);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param string $ipAddress
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $ipAddress): void
    {
        $this->delete(\sprintf('floating_ips/%s', $ipAddress));
    }

    /**
     * @param string $ipAddress
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions(string $ipAddress)
    {
        $actions = $this->get(\sprintf('floating_ips/%s/actions', $ipAddress));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param string $ipAddress
     * @param int    $actionId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getActionById(string $ipAddress, int $actionId)
    {
        $action = $this->get(\sprintf('floating_ips/%s/actions/%d', $ipAddress, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @param string $ipAddress
     * @param int    $dropletId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function assign(string $ipAddress, int $dropletId)
    {
        return $this->executeAction($ipAddress, ['type' => 'assign', 'droplet_id' => $dropletId]);
    }

    /**
     * @param string $ipAddress
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function unassign(string $ipAddress)
    {
        return $this->executeAction($ipAddress, ['type' => 'unassign']);
    }

    /**
     * @param string $ipAddress
     * @param array  $options
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    private function executeAction(string $ipAddress, array $options)
    {
        $action = $this->post(\sprintf('floating_ips/%s/actions', $ipAddress), $options);

        return new ActionEntity($action->action);
    }
}
