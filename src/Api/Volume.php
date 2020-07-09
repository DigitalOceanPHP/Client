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

use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\Snapshot as SnapshotEntity;
use DigitalOceanV2\Entity\Volume as VolumeEntity;

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
    public function getAll($regionSlug = null)
    {
        $regionQueryParameter = is_null($regionSlug) ? '' : sprintf('&region=%s', $regionSlug);
        $volumes = $this->httpClient->get(sprintf('%s/volumes?per_page=%d%s', $this->endpoint, 200, $regionQueryParameter));

        $volumes = json_decode($volumes);

        $this->extractMeta($volumes);

        return array_map(function ($volume) {
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
    public function getByNameAndRegion($driveName, $regionSlug)
    {
        $volumes = $this->httpClient->get(sprintf('%s/volumes?per_page=%d&region=%s&name=%s', $this->endpoint, 200, $regionSlug, $driveName));

        $volumes = json_decode($volumes);

        $this->extractMeta($volumes);

        return array_map(function ($volume) {
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
    public function getById($id)
    {
        $volume = $this->httpClient->get(sprintf('%s/volumes/%s?per_page=%d', $this->endpoint, $id, 200));

        $volume = json_decode($volume);

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
    public function getSnapshots($id)
    {
        $snapshots = $this->httpClient->get(sprintf('%s/volumes/%s/snapshots?per_page=%d', $this->endpoint, $id, 200));

        $snapshots = json_decode($snapshots);

        $this->meta = $this->extractMeta($snapshots);

        return array_map(function ($snapshot) {
            $snapshot = new SnapshotEntity($snapshot);

            return $snapshot;
        }, $snapshots->snapshots);
    }

    /**
     * @param string $name            A human-readable name for the Block Storage volume
     * @param string $description     Free-form text field to describe a Block Storage volume
     * @param string $sizeInGigabytes The size of the Block Storage volume in GiB
     * @param string $regionSlug      The region where the Block Storage volume will be created
     * @param string $snapshotId      The unique identifier for the volume snapshot from which to create the volume. Should not be specified with a region_id.
     * @param string $filesystemType  the name of the filesystem type to be used on the volume
     * @param string $filesystemLabel the label to be applied to the filesystem
     *
     * @throws ExceptionInterface
     *
     * @return VolumeEntity
     */
    public function create($name, $description, $sizeInGigabytes, $regionSlug, $snapshotId = null, $filesystemType = null, $filesystemLabel = null)
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

        $volume = $this->httpClient->post(sprintf('%s/volumes', $this->endpoint), $data);

        $volume = json_decode($volume);

        return new VolumeEntity($volume->volume);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function delete($id)
    {
        $this->httpClient->delete(sprintf('%s/volumes/%s', $this->endpoint, $id));
    }

    /**
     * @param string $driveName  restricts the search to volumes with the specified name
     * @param string $regionSlug restricts the search to volumes available in a specific region
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function deleteWithNameAndRegion($driveName, $regionSlug)
    {
        $this->httpClient->delete(sprintf('%s/volumes?name=%s&region=%s', $this->endpoint, $driveName, $regionSlug));
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
    public function attach($id, $dropletId, $regionSlug)
    {
        $data = [
            'type' => 'attach',
            'droplet_id' => $dropletId,
            'region' => $regionSlug,
        ];

        $action = $this->httpClient->post(sprintf('%s/volumes/%s/actions', $this->endpoint, $id), $data);

        $action = json_decode($action);

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
    public function detach($id, $dropletId, $regionSlug)
    {
        $data = [
            'type' => 'detach',
            'droplet_id' => $dropletId,
            'region' => $regionSlug,
        ];

        $action = $this->httpClient->post(sprintf('%s/volumes/%s/actions', $this->endpoint, $id), $data);

        $action = json_decode($action);

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
    public function resize($id, $newSize, $regionSlug)
    {
        $data = [
            'type' => 'resize',
            'size_gigabytes' => $newSize,
            'region' => $regionSlug,
        ];

        $action = $this->httpClient->post(sprintf('%s/volumes/%s/actions', $this->endpoint, $id), $data);

        $action = json_decode($action);

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
     * @throws ExceptionInterface
     *
     * @return SnapshotEntity
     */
    public function snapshot($id, $name)
    {
        $data = [
            'name' => $name,
        ];

        $snapshot = $this->httpClient->post(sprintf('%s/volumes/%s/snapshots', $this->endpoint, $id), $data);

        $snapshot = json_decode($snapshot);

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
    public function getActionById($id, $actionId)
    {
        $action = $this->httpClient->get(sprintf('%s/volumes/%s/actions/%d', $this->endpoint, $id, $actionId));

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getActions($id)
    {
        $actions = $this->httpClient->get(sprintf('%s/volumes/%s/actions?per_page=%d', $this->endpoint, $id, 200));

        $actions = json_decode($actions);

        $this->meta = $this->extractMeta($actions);

        return array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }
}
