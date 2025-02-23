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
use DigitalOceanV2\Entity\Droplet as DropletEntity;
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Entity\Kernel as KernelEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Droplet extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return DropletEntity[]
     */
    public function getAll(?string $tag = null): array
    {
        $droplets = $this->get('droplets', null === $tag ? [] : ['tag_name' => $tag]);

        return \array_map(function ($droplet) {
            return new DropletEntity($droplet);
        }, $droplets->droplets);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DropletEntity[]
     */
    public function getNeighborsById(int $id): array
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
    public function getAllNeighbors(): array
    {
        $neighbors = $this->get('reports/droplet_neighbors');

        return \array_map(function ($neighbor) {
            return new DropletEntity($neighbor);
        }, $neighbors->neighbors);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(int $id): DropletEntity
    {
        $droplet = $this->get(\sprintf('droplets/%d', $id));

        return new DropletEntity($droplet->droplet);
    }

    /**
     * @param int[] $sshKeys
     *
     * @throws ExceptionInterface
     *
     * @return DropletEntity|DropletEntity[]|null
     */
    public function create(array|string $names, string $region, string $size, string|int $image, bool $backups = false, bool $ipv6 = false, string|bool $vpcUuid = false, array $sshKeys = [], string $userData = '', bool $monitoring = true, array $volumes = [], array $tags = [], bool $disableAgent = false): DropletEntity|array|null
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

        if ($disableAgent) {
            $data['with_droplet_agent'] = 'false';
        }

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
     * @throws ExceptionInterface
     */
    public function remove(int $id): void
    {
        $this->delete(\sprintf('droplets/%d', $id));
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeTagged(string $tag): void
    {
        $this->delete('droplets', [], [], ['tag_name' => $tag]);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return KernelEntity[]
     */
    public function getAvailableKernels(int $id): array
    {
        $kernels = $this->get(\sprintf('droplets/%d/kernels', $id));

        return \array_map(function ($kernel) {
            return new KernelEntity($kernel);
        }, $kernels->kernels);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getSnapshots(int $id): array
    {
        $snapshots = $this->get(\sprintf('droplets/%d/snapshots', $id));

        return \array_map(function ($snapshot) {
            return new ImageEntity($snapshot);
        }, $snapshots->snapshots);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getBackups(int $id): array
    {
        $backups = $this->get(\sprintf('droplets/%d/backups', $id));

        return \array_map(function ($backup) {
            return new ImageEntity($backup);
        }, $backups->backups);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions(int $id): array
    {
        $actions = $this->get(\sprintf('droplets/%d/actions', $id));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getActionById(int $id, int $actionId): ActionEntity
    {
        $action = $this->get(\sprintf('droplets/%d/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @throws ExceptionInterface
     */
    public function reboot(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'reboot']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function powerCycle(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'power_cycle']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function shutdown(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'shutdown']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function powerOff(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'power_off']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function powerOn(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'power_on']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function passwordReset(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'password_reset']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function resize(int $id, string $size, bool $disk = true): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'resize', 'size' => $size, 'disk' => $disk ? 'true' : 'false']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function restore(int $id, int $image): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'restore', 'image' => $image]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function rebuild(int $id, int|string $image): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'rebuild', 'image' => $image]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function rename(int $id, string $name): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'rename', 'name' => $name]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function changeKernel(int $id, int $kernel): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'change_kernel', 'kernel' => $kernel]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function enableIpv6(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'enable_ipv6']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function enableBackups(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'enable_backups']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function disableBackups(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'disable_backups']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function enablePrivateNetworking(int $id): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'enable_private_networking']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function snapshot(int $id, string $name): ActionEntity
    {
        return $this->executeAction($id, ['type' => 'snapshot', 'name' => $name]);
    }

    /**
     * @throws ExceptionInterface
     */
    private function executeAction(int $id, array $options): ActionEntity
    {
        $action = $this->post(\sprintf('droplets/%d/actions', $id), $options);

        return new ActionEntity($action->action);
    }
}
