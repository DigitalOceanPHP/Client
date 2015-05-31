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
use DigitalOceanV2\Entity\Droplet as DropletEntity;
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Entity\Kernel as KernelEntity;
use DigitalOceanV2\Entity\Upgrade as UpgradeEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Droplet extends AbstractApi
{
    /**
     * @return DropletEntity[]
     */
    public function getAll()
    {
        $droplets = $this->adapter->get(sprintf('%s/droplets?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
        $droplets = json_decode($droplets);

        $this->extractMeta($droplets);

        return array_map(function ($droplet) {
            return new DropletEntity($droplet);
        }, $droplets->droplets);
    }

    /**
     * @param int $id
     *
     * @return DropletEntity[]
     */
    public function getNeighborsById($id)
    {
        $droplets = $this->adapter->get(sprintf('%s/droplets/%d/neighbors', self::ENDPOINT, $id));
        $droplets = json_decode($droplets);

        return array_map(function ($droplet) {
            return new DropletEntity($droplet);
        }, $droplets->droplets);
    }

    /**
     * @return DropletEntity[]
     */
    public function getAllNeighbors()
    {
        $neighbors = $this->adapter->get(sprintf('%s/reports/droplet_neighbors', self::ENDPOINT));
        $neighbors = json_decode($neighbors);

        return array_map(function ($neighbor) {
            return new DropletEntity($neighbor);
        }, $neighbors->neighbors);
    }

    /**
     * @return UpgradeEntity[]
     */
    public function getUpgrades()
    {
        $upgrades = $this->adapter->get(sprintf('%s/droplet_upgrades', self::ENDPOINT));
        $upgrades = json_decode($upgrades);

        return array_map(function ($upgrade) {
            return new UpgradeEntity($upgrade);
        }, $upgrades);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return DropletEntity
     */
    public function getById($id)
    {
        $droplet = $this->adapter->get(sprintf('%s/droplets/%d', self::ENDPOINT, $id));
        $droplet = json_decode($droplet);

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param string     $name
     * @param string     $region
     * @param string     $size
     * @param string|int $image
     * @param bool       $backups           (optional)
     * @param bool       $ipv6              (optional)
     * @param bool       $privateNetworking (optional)
     * @param int[]      $sshKeys           (optional)
     * @param string     $userData          (optional)
     *
     * @throws \RuntimeException
     *
     * @return DropletEntity
     */
    public function create($name, $region, $size, $image, $backups = false, $ipv6 = false,
        $privateNetworking = false, array $sshKeys = array(), $userData = ""
    ) {
        $headers = array('Content-Type: application/json');

        $data = array(
            'name' => $name,
            'region' => $region,
            'size' => $size,
            'image' => $image,
            'backups' => \DigitalOceanV2\bool_to_string($backups),
            'ipv6' => \DigitalOceanV2\bool_to_string($ipv6),
            'private_networking' => \DigitalOceanV2\bool_to_string($privateNetworking)
        );

        if (0 < count($sshKeys)) {
            $data["ssh_keys"] = $sshKeys;
        }

        if (!empty($userData)) {
            $data["user_data"] = $userData;
        }

        $content = json_encode($data);

        $droplet = $this->adapter->post(sprintf('%s/droplets', self::ENDPOINT), $headers, $content);
        $droplet = json_decode($droplet);

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     */
    public function delete($id)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/droplets/%d', self::ENDPOINT, $id), $headers);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return KernelEntity[]
     */
    public function getAvailableKernels($id)
    {
        $kernels = $this->adapter->get(sprintf('%s/droplets/%d/kernels', self::ENDPOINT, $id));
        $kernels = json_decode($kernels);

        $this->meta = $this->extractMeta($kernels);

        return array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getSnapshots($id)
    {
        $snapshots = $this->adapter->get(sprintf('%s/droplets/%d/snapshots?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
        $snapshots = json_decode($snapshots);

        $this->meta = $this->extractMeta($snapshots);

        return array_map(function ($snapshot) {
            $snapshot = new ImageEntity($snapshot);

            return $snapshot;
        }, $snapshots->snapshots);
    }

    /**
     * @param int $id
     *
     * @return ImageEntity[]
     */
    public function getBackups($id)
    {
        $backups = $this->adapter->get(sprintf('%s/droplets/%d/backups?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
        $backups = json_decode($backups);

        $this->meta = $this->extractMeta($backups);

        return array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
    }

    /**
     * @param int $id
     *
     * @return ActionEntity[]
     */
    public function getActions($id)
    {
        $actions = $this->adapter->get(sprintf('%s/droplets/%d/actions?per_page=%d', self::ENDPOINT, $id, PHP_INT_MAX));
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
        $action = $this->adapter->get(sprintf('%s/droplets/%d/actions/%d', self::ENDPOINT, $id, $actionId));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function reboot($id)
    {
        return $this->executeAction($id, array('type' => 'reboot'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerCycle($id)
    {
        return $this->executeAction($id, array('type' => 'power_cycle'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function shutdown($id)
    {
        return $this->executeAction($id, array('type' => 'shutdown'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerOff($id)
    {
        return $this->executeAction($id, array('type' => 'power_off'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function powerOn($id)
    {
        return $this->executeAction($id, array('type' => 'power_on'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function passwordReset($id)
    {
        return $this->executeAction($id, array('type' => 'password_reset'));
    }

    /**
     * @param int    $id
     * @param string $size
     * @param bool   $disk  (optional)
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function resize($id, $size, $disk = true)
    {
        return $this->executeAction($id, array('type' => 'resize', 'size' => $size, 'disk' => $disk));
    }

    /**
     * @param int $id
     * @param int $image
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function restore($id, $image)
    {
        return $this->executeAction($id, array('type' => 'restore', 'image' => $image));
    }

    /**
     * @param int        $id
     * @param int|string $image
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function rebuild($id, $image)
    {
        return $this->executeAction($id, array('type' => 'rebuild', 'image' => $image));
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function rename($id, $name)
    {
        return $this->executeAction($id, array('type' => 'rename', 'name' => $name));
    }

    /**
     * @param int $id
     * @param int $kernel
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function changeKernel($id, $kernel)
    {
        return $this->executeAction($id, array('type' => 'change_kernel', 'kernel' => $kernel));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function enableIpv6($id)
    {
        return $this->executeAction($id, array('type' => 'enable_ipv6'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function disableBackups($id)
    {
        return $this->executeAction($id, array('type' => 'disable_backups'));
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function enablePrivateNetworking($id)
    {
        return $this->executeAction($id, array('type' => 'enable_private_networking'));
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    public function snapshot($id, $name)
    {
        return $this->executeAction($id, array('type' => 'snapshot', 'name' => $name));
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @throws \RuntimeException
     *
     * @return ActionEntity
     */
    private function executeAction($id, array $options)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode($options);

        $action = $this->adapter->post(sprintf('%s/droplets/%d/actions', self::ENDPOINT, $id), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
