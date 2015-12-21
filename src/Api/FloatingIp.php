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

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\FloatingIp as FloatingIpEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
class FloatingIp extends AbstractApi
{
    /**
     * @return FloatingIpEntity[]
     */
    public function getAll()
    {
        $query = sprintf('%s/floating_ips?per_page=%d', $this->endpoint, 200);

        $ips = $this->adapter->get($query);

        $ips = json_decode($ips);

        $this->extractMeta($ips);

        return array_map(function ($ip) {
            return new FloatingIpEntity($ip);
        }, $ips->floating_ips);
    }

    /**
     * @param int $id
     *
     * @return FloatingIpEntity
     */
    public function getById($id)
    {
        $ip = $this->adapter->get(sprintf('%s/floating_ips/%s', $this->endpoint, $id));

        $ip = json_decode($ip);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param int $dropletId
     *
     * @throws HttpException
     *
     * @return FloatingIpEntity
     */
    public function createAssigned($dropletId)
    {
        $ip = $this->adapter->post(sprintf('%s/floating_ips', $this->endpoint), ['droplet_id' => $dropletId]);

        $ip = json_decode($ip);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param string $regionSlug
     *
     * @throws HttpException
     *
     * @return FloatingIpEntity
     */
    public function createReserved($regionSlug)
    {
        $ip = $this->adapter->post(sprintf('%s/floating_ips', $this->endpoint), ['region' => $regionSlug]);

        $ip = json_decode($ip);

        return new FloatingIpEntity($ip->floating_ip);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     */
    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/floating_ips/%s', $this->endpoint, $id));
    }

    /**
     * @param int $id
     *
     * @return ActionEntity[]
     */
    public function getActions($id)
    {
        $actions = $this->adapter->get(sprintf('%s/floating_ips/%s/actions?per_page=%d', $this->endpoint, $id, 200));

        $actions = json_decode($actions);

        $this->meta = $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @return ActionEntity
     */
    public function getActionById($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/floating_ips/%s/actions/%d', $this->endpoint, $id, $actionId));

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     * @param int $dropletId
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function assign($id, $dropletId)
    {
        return $this->executeAction($id, ['type' => 'assign', 'droplet_id' => $dropletId]);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function unassign($id)
    {
        return $this->executeAction($id, ['type' => 'unassign']);
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    private function executeAction($id, array $options)
    {
        $action = $this->adapter->post(sprintf('%s/floating_ips/%s/actions', $this->endpoint, $id), $options);

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
