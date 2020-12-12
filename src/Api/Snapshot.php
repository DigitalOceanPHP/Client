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

use DigitalOceanV2\Entity\Snapshot as SnapshotEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Snapshot extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @throws ExceptionInterface
     *
     * @return SnapshotEntity[]
     */
    public function getAll(array $criteria = [])
    {
        $query = [];

        if (isset($criteria['type']) && \in_array($criteria['type'], ['droplet', 'volume'], true)) {
            $query['resource_type'] = $criteria['type'];
        }

        $snapshots = $this->get('snapshots', $query);

        return \array_map(function ($snapshots) {
            return new SnapshotEntity($snapshots);
        }, $snapshots->snapshots);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return SnapshotEntity
     */
    public function getById(string $id)
    {
        $snapshot = $this->get(\sprintf('snapshots/%s', $id));

        return new SnapshotEntity($snapshot->snapshot);
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
        $this->delete(\sprintf('snapshots/%s', $id));
    }
}
