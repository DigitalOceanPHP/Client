<?php

/**
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
        $droplets = $this->adapter->get(sprintf('%s/droplets', self::ENDPOINT));
        $droplets = json_decode($droplets);

        $meta = $this->getMeta($droplets);

        return array_map(function ($droplet) use ($meta) {
            $droplet = new DropletEntity($droplet);
            $droplet->meta = $meta;

            return $droplet;
        }, $droplets->droplets);
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return DropletEntity
     */
    public function getById($id)
    {
        $droplet = $this->adapter->get(sprintf('%s/droplets/%d', self::ENDPOINT, $id));
        $droplet = json_decode($droplet);

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param  string            $name
     * @param  string            $region
     * @param  string            $size
     * @param  string|integer    $image
     * @param  boolean           $backups (optional)
     * @param  boolean           $ipv6 (optional)
     * @param  boolean           $privateNetworking (optional)
     * @param  integer[]         $sshKeys (optional)
     * @throws \RuntimeException
     * @return DropletEntity
     */
    public function create($name, $region, $size, $image, $backups = false, $ipv6 = false,
        $privateNetworking = false, array $sshKeys = array()
    ) {
        $headers = array('Content-Type: application/json');

        $sshIds  = '';
        if (0 < count($sshKeys)) {
            $sshIds = sprintf(',"ssh_keys":[%s]', implode(',', $sshKeys));
        }

        // image can be either image id or a public image slug
        $image = is_int($image) ? $image : sprintf('"%s"', $image);

        $content = sprintf(
            '{"name":"%s","region":"%s","size":"%s","image":%s,"backups":%s,"ipv6":%s,"private_networking":%s%s}',
            $name, $region, $size, $image,
            \DigitalOceanV2\bool_to_string($backups),
            \DigitalOceanV2\bool_to_string($ipv6),
            \DigitalOceanV2\bool_to_string($privateNetworking), $sshIds
        );

        $droplet = $this->adapter->post(sprintf('%s/droplets', self::ENDPOINT), $headers, $content);
        $droplet = json_decode($droplet);

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     */
    public function delete($id)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/droplets/%d', self::ENDPOINT, $id), $headers);
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return KernelEntity[]
     */
    public function getAvailableKernels($id)
    {
        $kernels = $this->adapter->get(sprintf('%s/droplets/%d/kernels', self::ENDPOINT, $id));
        $kernels = json_decode($kernels);

        $meta = $this->getMeta($kernels);

        return array_map(function ($kernel) use ($meta) {
            $kernel = new KernelEntity($kernel);
            $kernel->meta = $meta;

            return $kernel;
        }, $kernels->kernels);
    }

    /**
     * @param  integer       $id
     * @return ImageEntity[]
     */
    public function getSnapshots($id)
    {
        $snapshots = $this->adapter->get(sprintf('%s/droplets/%d/snapshots', self::ENDPOINT, $id));
        $snapshots = json_decode($snapshots);

        $meta = $this->getMeta($snapshots);

        return array_map(function ($snapshot) use ($meta) {
            $snapshot = new ImageEntity($snapshot);
            $snapshot->meta = $meta;

            return $snapshot;
        }, $snapshots->snapshots);
    }

    /**
     * @param  integer       $id
     * @return ImageEntity[]
     */
    public function getBackups($id)
    {
        $backups = $this->adapter->get(sprintf('%s/droplets/%d/backups', self::ENDPOINT, $id));
        $backups = json_decode($backups);

        $meta = $this->getMeta($backups);

        return array_map(function ($backup) use ($meta) {
            $backup = new ImageEntity($backup);
            $backup->meta = $meta;

            return $backup;
        }, $backups->backups);
    }

    public function getActions($id)
    {
        $actions = $this->adapter->get(sprintf('%s/droplets/%d/actions', self::ENDPOINT, $id));
        $actions = json_decode($actions);

        $meta = $this->getMeta($actions);

        return array_map(function ($action) use ($meta) {
            $action = new ActionEntity($action);
            $action->meta = $meta;

            return $action;
        }, $actions->actions);
    }

    /**
     * @param  integer      $id
     * @param  integer      $actionId
     * @return ActionEntity
     */
    public function getActionById($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/droplets/%d/actions/%d', self::ENDPOINT, $id, $actionId));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function reboot($id)
    {
        return $this->executeAction($id, array('type' => 'reboot'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerCycle($id)
    {
        return $this->executeAction($id, array('type' => 'power_cycle'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function shutdown($id)
    {
        return $this->executeAction($id, array('type' => 'shutdown'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerOff($id)
    {
        return $this->executeAction($id, array('type' => 'power_off'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerOn($id)
    {
        return $this->executeAction($id, array('type' => 'power_on'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function passwordReset($id)
    {
        return $this->executeAction($id, array('type' => 'password_reset'));
    }

    /**
     * @param  integer           $id
     * @param  string            $size
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function resize($id, $size)
    {
        return $this->executeAction($id, array('type' => 'resize', 'size' => $size));
    }

    /**
     * @param  integer           $id
     * @param  integer           $image
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function restore($id, $image)
    {
        return $this->executeAction($id, array('type' => 'restore', 'image' => $image));
    }

    /**
     * @param  integer           $id
     * @param  integer|string    $image
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function rebuild($id, $image)
    {
        return $this->executeAction($id, array('type' => 'rebuild', 'image' => $image));
    }

    /**
     * @param  integer           $id
     * @param  string            $name
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function rename($id, $name)
    {
        return $this->executeAction($id, array('type' => 'rename', 'name' => $name));
    }

    /**
     * @param  integer           $id
     * @param  integer           $kernel
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function changeKernel($id, $kernel)
    {
        return $this->executeAction($id, array('type' => 'change_kernel', 'kernel' => $kernel));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function enableIpv6($id)
    {
        return $this->executeAction($id, array('type' => 'enable_ipv6'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function disableBackups($id)
    {
        return $this->executeAction($id, array('type' => 'disable_backups'));
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function enablePrivateNetworking($id)
    {
        return $this->executeAction($id, array('type' => 'enable_private_networking'));
    }

    /**
     * @param  integer           $id
     * @param  array             $options
     * @throws \RuntimeException
     * @return ActionEntity
     */
    private function executeAction($id, array $options){
        $headers = array('Content-Type: application/json');
        $content = json_encode($options);

        $action = $this->adapter->post(sprintf('%s/droplets/%d/actions', self::ENDPOINT, $id), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
