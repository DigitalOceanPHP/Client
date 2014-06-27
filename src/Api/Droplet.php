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
}
