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
use DigitalOceanV2\Entity\Droplet as DropletEntity;
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Entity\Kernel as KernelEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Droplet extends AbstractApi
{
    /**
     * @param string|null $tag
     *
     * @throws ExceptionInterface
     *
     * @return DropletEntity[]
     */
    public function getAll(?string $tag = null)
    {
        $droplets = $this->get('droplets', null === $tag ? [] : ['tag_name' => $tag]);

        return \array_map(function ($droplet) {
            return new DropletEntity($droplet);
        }, $droplets->droplets);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return DropletEntity[]
     */
    public function getNeighborsById(int $id)
    {
        $droplets = $this->get(\sprintf('droplets/%d/neighbors', $id));

        return \array_map(function ($droplet) {
            return new DropletEntity($droplet);
        }, $droplets->droplets);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DropletEntity[]
     */
    public function getAllNeighbors()
    {
        $neighbors = $this->get('reports/droplet_neighbors');

        return \array_map(function ($neighbor) {
            return new DropletEntity($neighbor);
        }, $neighbors->neighbors);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return DropletEntity
     */
    public function getById(int $id)
    {
        $droplet = $this->get(\sprintf('droplets/%d', $id));

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param array|string $names
     * @param string       $region
     * @param string       $size
     * @param string|int   $image
     * @param bool         $backups
     * @param bool         $ipv6
     * @param string|bool  $vpcUuid
     * @param int[]        $sshKeys
     * @param string       $userData
     * @param bool         $monitoring
     * @param array        $volumes
     * @param array        $tags
     *
     * @throws ExceptionInterface
     *
     * @return DropletEntity|DropletEntity[]|null
     */
    public function create($names, string $region, string $size, $image, bool $backups = false, bool $ipv6 = false, $vpcUuid = false, array $sshKeys = [], string $userData = '', bool $monitoring = true, array $volumes = [], array $tags = [])
    {
        $data = \is_array($names) ? ['names' => $names] : ['name' => $names];

        $data = \array_merge($data, [
            'region' => $region,
            'size' => $size,
            'image' => $image,
            'backups' => $backups ? 'true' : 'false',
            'ipv6' => $ipv6 ? 'true' : 'false',
            'monitoring' => $monitoring ? 'true' : 'false',
        ]);

        if (0 < \count($sshKeys)) {
            $data['ssh_keys'] = $sshKeys;
        }

        if ('' !== $userData) {
            $data['user_data'] = $userData;
        }

        if (\is_bool($vpcUuid)) {
            $data['private_networking'] = $vpcUuid ? 'true' : 'false';
        } elseif ('' !== $vpcUuid) {
            $data['vpc_uuid'] = $vpcUuid;
        }

        if (0 < \count($volumes)) {
            $data['volumes'] = $volumes;
        }

        if (0 < \count($tags)) {
            $data['tags'] = $tags;
        }

        $droplet = $this->post('droplets', $data);

        if (\is_array($names)) {
            return \array_map(function ($droplet) {
                return new DropletEntity($droplet);
            }, $droplet->droplets);
        }

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(int $id): void
    {
        $this->delete(\sprintf('droplets/%d', $id));
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return KernelEntity[]
     */
    public function getAvailableKernels(int $id)
    {
        $kernels = $this->get(\sprintf('droplets/%d/kernels', $id));

        return \array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getSnapshots(int $id)
    {
        $snapshots = $this->get(\sprintf('droplets/%d/snapshots', $id));

        return \array_map(function ($snapshot) {
            return new ImageEntity($snapshot);
        }, $snapshots->snapshots);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getBackups(int $id)
    {
        $backups = $this->get(\sprintf('droplets/%d/backups', $id));

        return \array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
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
        $actions = $this->get(\sprintf('droplets/%d/actions', $id));

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
        $action = $this->get(\sprintf('droplets/%d/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function reboot(int $id)
    {
        return $this->executeAction($id, ['type' => 'reboot']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function powerCycle(int $id)
    {
        return $this->executeAction($id, ['type' => 'power_cycle']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function shutdown(int $id)
    {
        return $this->executeAction($id, ['type' => 'shutdown']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function powerOff(int $id)
    {
        return $this->executeAction($id, ['type' => 'power_off']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function powerOn(int $id)
    {
        return $this->executeAction($id, ['type' => 'power_on']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function passwordReset(int $id)
    {
        return $this->executeAction($id, ['type' => 'password_reset']);
    }

    /**
     * @param int    $id
     * @param string $size
     * @param bool   $disk
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function resize(int $id, string $size, bool $disk = true)
    {
        return $this->executeAction($id, ['type' => 'resize', 'size' => $size, 'disk' => $disk ? 'true' : 'false']);
    }

    /**
     * @param int $id
     * @param int $image
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function restore(int $id, int $image)
    {
        return $this->executeAction($id, ['type' => 'restore', 'image' => $image]);
    }

    /**
     * @param int        $id
     * @param int|string $image
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function rebuild(int $id, $image)
    {
        return $this->executeAction($id, ['type' => 'rebuild', 'image' => $image]);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function rename(int $id, string $name)
    {
        return $this->executeAction($id, ['type' => 'rename', 'name' => $name]);
    }

    /**
     * @param int $id
     * @param int $kernel
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function changeKernel(int $id, int $kernel)
    {
        return $this->executeAction($id, ['type' => 'change_kernel', 'kernel' => $kernel]);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function enableIpv6(int $id)
    {
        return $this->executeAction($id, ['type' => 'enable_ipv6']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function enableBackups(int $id)
    {
        return $this->executeAction($id, ['type' => 'enable_backups']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function disableBackups(int $id)
    {
        return $this->executeAction($id, ['type' => 'disable_backups']);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function enablePrivateNetworking(int $id)
    {
        return $this->executeAction($id, ['type' => 'enable_private_networking']);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function snapshot(int $id, string $name)
    {
        return $this->executeAction($id, ['type' => 'snapshot', 'name' => $name]);
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
        $action = $this->post(\sprintf('droplets/%d/actions', $id), $options);

        return new ActionEntity($action->action);
    }
}
