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
use DigitalOceanV2\Entity\Snapshot as SnapshotEntity;
use DigitalOceanV2\Entity\Volume as VolumeEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Volume extends AbstractApi
{
    /**
     * @param string $regionSlug restricts results to volumes available in a specific region
     *
     * @throws ExceptionInterface
     *
     * @return VolumeEntity[] Lists all of the Block Storage volumes available
     */
    public function getAll(string $regionSlug = null)
    {
        $query = null === $regionSlug ? [] : ['region' => $regionSlug];

        $volumes = $this->get('volumes', $query);

        return \array_map(function ($volume) {
            return new VolumeEntity($volume);
        }, $volumes->volumes);
    }

    /**
     * @param string $driveName  restricts results to volumes with the specified name
     * @param string $regionSlug restricts results to volumes available in a specific region
     *
     * @throws ExceptionInterface
     *
     * @return VolumeEntity[] Lists all of the Block Storage volumes available
     */
    public function getByNameAndRegion(string $driveName, string $regionSlug)
    {
        $volumes = $this->get(\sprintf('volumes&region=%s&name=%s', $regionSlug, $driveName));

        return \array_map(function ($volume) {
            return new VolumeEntity($volume);
        }, $volumes->volumes);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return VolumeEntity the Block Storage volume with the specified id
     */
    public function getById(string $id)
    {
        $volume = $this->get(\sprintf('volumes/%s', $id));

        return new VolumeEntity($volume->volume);
    }

    /**
     * Get all volume snapshots.
     *
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return SnapshotEntity[]
     */
    public function getSnapshots(string $id)
    {
        $snapshots = $this->get(\sprintf('volumes/%s/snapshots', $id));

        return \array_map(function ($snapshot) {
            return new SnapshotEntity($snapshot);
        }, $snapshots->snapshots);
    }

    /**
     * @param string $name            A human-readable name for the Block Storage volume
     * @param string $description     Free-form text field to describe a Block Storage volume
     * @param int    $sizeInGigabytes The size of the Block Storage volume in GiB
     * @param string $regionSlug      The region where the Block Storage volume will be created
     * @param string $snapshotId      The unique identifier for the volume snapshot from which to create the volume. Should not be specified with a region_id.
     * @param string $filesystemType  the name of the filesystem type to be used on the volume
     * @param string $filesystemLabel the label to be applied to the filesystem
     *
     * @throws ExceptionInterface
     *
     * @return VolumeEntity
     */
    public function create(string $name, string $description, int $sizeInGigabytes, string $regionSlug, string $snapshotId = null, string $filesystemType = null, string $filesystemLabel = null)
    {
        $data = [
            'size_gigabytes' => $sizeInGigabytes,
            'name' => $name,
            'description' => $description,
            'region' => $regionSlug,
        ];

        if (null !== $snapshotId) {
            $data['snapshot_id'] = $snapshotId;
        }
        if (null !== $filesystemType) {
            $data['filesystem_type'] = $filesystemType;
        }
        if (null !== $filesystemLabel) {
            $data['filesystem_label'] = $filesystemLabel;
        }

        $volume = $this->post('volumes', $data);

        return new VolumeEntity($volume->volume);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('volumes/%s', $id));
    }

    /**
     * @param string $driveName  restricts the search to volumes with the specified name
     * @param string $regionSlug restricts the search to volumes available in a specific region
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeWithNameAndRegion(string $driveName, string $regionSlug): void
    {
        $this->delete('volumes', [
            'name' => $driveName,
            'region' => $regionSlug,
        ]);
    }

    /**
     * @param string $id         the id of the volume
     * @param int    $dropletId  the unique identifier for the Droplet the volume will be attached to
     * @param string $regionSlug the slug identifier for the region the volume is located in
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function attach(string $id, int $dropletId, string $regionSlug)
    {
        $action = $this->post(\sprintf('volumes/%s/actions', $id), [
            'type' => 'attach',
            'droplet_id' => $dropletId,
            'region' => $regionSlug,
        ]);

        return new ActionEntity($action->action);
    }

    /**
     * @param string $id         the id of the volume
     * @param int    $dropletId  the unique identifier for the Droplet the volume will detach from
     * @param string $regionSlug the slug identifier for the region the volume is located in
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function detach(string $id, int $dropletId, string $regionSlug)
    {
        $action = $this->post(\sprintf('volumes/%s/actions', $id), [
            'type' => 'detach',
            'droplet_id' => $dropletId,
            'region' => $regionSlug,
        ]);

        return new ActionEntity($action->action);
    }

    /**
     * @param string $id         the id of the volume
     * @param int    $newSize    the new size of the Block Storage volume in GiB
     * @param string $regionSlug the slug identifier for the region the volume is located in
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function resize(string $id, int $newSize, string $regionSlug)
    {
        $action = $this->post(\sprintf('volumes/%s/actions', $id), [
            'type' => 'resize',
            'size_gigabytes' => $newSize,
            'region' => $regionSlug,
        ]);

        return new ActionEntity($action->action);
    }

    /**
     * Create a new snapshot of the volume.
     *
     * @param string $id   the id of the volume
     * @param string $name a human-readable name for the volume snapshot
     *
     * @throws ExceptionInterface
     *
     * @return SnapshotEntity
     */
    public function snapshot(string $id, string $name)
    {
        $snapshot = $this->post(\sprintf('volumes/%s/snapshots', $id), ['name' => $name]);

        return new SnapshotEntity($snapshot->snapshot);
    }

    /**
     * @param string $id
     * @param int    $actionId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getActionById(string $id, int $actionId)
    {
        $action = $this->get(\sprintf('volumes/%s/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions(string $id)
    {
        $actions = $this->get(\sprintf('volumes/%s/actions', $id));

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }
}
