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
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return FloatingIpEntity
     */
    public function getById(int $id)
    {
        $ip = $this->get(\sprintf('floating_ips/%s', $id));

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
        $ip = $this->post(\sprintf('floating_ips'), ['region' => $regionSlug]);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(int $id)
    {
        $this->delete(\sprintf('floating_ips/%s', $id));
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions(int $id)
    {
        $actions = $this->get(\sprintf('floating_ips/%s/actions', $id));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getActionById(int $id, int $actionId)
    {
        $action = $this->get(\sprintf('floating_ips/%s/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     * @param int $dropletId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function assign(int $id, int $dropletId)
    {
        return $this->executeAction($id, ['type' => 'assign', 'droplet_id' => $dropletId]);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function unassign(int $id)
    {
        return $this->executeAction($id, ['type' => 'unassign']);
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    private function executeAction(int $id, array $options)
    {
        $action = $this->post(\sprintf('floating_ips/%s/actions', $id), $options);

        return new ActionEntity($action->action);
    }
}
