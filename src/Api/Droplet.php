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

        return array_map(function ($droplet) {
            return new DropletEntity($droplet);
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
     * @param  string            $image
     * @param  boolean           $backups (optional)
     * @param  boolean           $ipv6 (optional)
     * @param  boolean           $privateNetworking (optional)
     * @param  integer[]         $sshKeys (optional)
     * @throws \RuntimeException
     * @return DropletEntity
     */
    public function create($name, $region, $size, $image, $backups = false, $ipv6 = false, $privateNetworking = false, $sshKeys = null)
    {
        $headers = array('Content-Type: application/json');
        $sshIds  = '';

        if(null !== $sshKeys && 0 < count($sshKeys)) {
            $sshIds = sprintf(',ssh_keys: [%s]', implode(',', $sshKeys));
        }

        $content = sprintf(
            '{"name":"%s","region":"%s","size":"%s","image":%d,"backups":%s,"ipv6":%s,"private_networking":%s%s}',
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
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return KernelEntity[]
     */
    public function getAvailableKernelsByDroplet($dropletId)
    {
        $kernels = $this->adapter->get(sprintf('%s/droplets/%d/kernels', self::ENDPOINT, $dropletId));
        $kernels = json_decode($kernels);

        return array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @param  integer       $dropletId
     * @return ImageEntity[]
     */
    public function getDropletSnapshots($dropletId)
    {
        $snapshots = $this->adapter->get(sprintf('%s/droplets/%d/snapshots', self::ENDPOINT, $dropletId));
        $snapshots = json_decode($snapshots);

        return array_map(function ($snapshot) {
            return new ImageEntity($snapshot);
        }, $snapshots->snapshots);
    }

    /**
     * @param  integer       $dropletId
     * @return ImageEntity[]
     */
    public function getDropletBackups($dropletId)
    {
        $backups = $this->adapter->get(sprintf('%s/droplets/%d/backups', self::ENDPOINT, $dropletId));
        $backups = json_decode($backups);

        return array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
    }

    /**
     * @param  integer      $dropletId
     * @param  integer      $actionId
     * @return ActionEntity
     */
    public function getDropletActionById($dropletId, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/droplets/%d/actions/%d', self::ENDPOINT, $dropletId, $actionId));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function reboot($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'reboot'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerCycle($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'power_cycle'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function shutdown($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'shutdown'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerOff($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'power_off'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function powerOn($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'power_on'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function passwordReset($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'password_reset'));
    }

    /**
     * @param  integer           $dropletId
     * @param  string            $size
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function resize($dropletId, $size)
    {
        return $this->executeAction($dropletId, array('type' => 'resize', 'size' => $size));
    }

    /**
     * @param  integer           $dropletId
     * @param  integer|string    $image
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function restore($dropletId, $image)
    {
        return $this->executeAction($dropletId, array('type' => 'resize', 'image' => $image));
    }

    /**
     * @param  integer           $dropletId
     * @param  integer|string    $image
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function rebuild($dropletId, $image)
    {
        return $this->executeAction($dropletId, array('type' => 'rebuild', 'image' => $image));
    }

    /**
     * @param  integer           $dropletId
     * @param  string            $name
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function rename($dropletId, $name)
    {
        return $this->executeAction($dropletId, array('type' => 'rename', 'image' => $name));
    }

    /**
     * @param  integer           $dropletId
     * @param  integer           $kernel
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function changeKernel($dropletId, $kernel)
    {
        return $this->executeAction($dropletId, array('type' => 'change_kernel', 'kernel' => $kernel));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function enableIpv6($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'enable_ipv6'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function disableBackups($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'disable_backups'));
    }

    /**
     * @param  integer           $dropletId
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function enablePrivateNetworking($dropletId)
    {
        return $this->executeAction($dropletId, array('type' => 'enable_private_networking'));
    }

    /**
     * @param  integer           $dropletId
     * @param  array             $options
     * @throws \RuntimeException
     * @return ActionEntity
     */
    private function executeAction($dropletId, array $options){
        $headers = array('Content-Type: application/json');
        $content = json_encode($options);

        $action = $this->adapter->post(sprintf('%s/droplets/%d/actions', self::ENDPOINT, $dropletId), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
