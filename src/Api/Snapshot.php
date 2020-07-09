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

use DigitalOceanV2\Entity\Snapshot as SnapshotEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Snapshot extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return SnapshotEntity[]
     */
    public function getAll(array $criteria = [])
    {
        $query = sprintf('%s/snapshots?per_page=%d', $this->endpoint, 200);

        if (isset($criteria['type']) && in_array($criteria['type'], ['droplet', 'volume'], true)) {
            $query = sprintf('%s&resource_type=%s', $query, $criteria['type']);
        }

        $snapshots = $this->httpClient->get($query);

        $snapshots = json_decode($snapshots);

        $this->extractMeta($snapshots);

        return array_map(function ($snapshots) {
            return new SnapshotEntity($snapshots);
        }, $snapshots->snapshots);
    }

    /**
     * @param string $id
     *
     * @return SnapshotEntity
     */
    public function getById($id)
    {
        $snapshot = $this->httpClient->get(sprintf('%s/snapshots/%s', $this->endpoint, $id));

        $snapshot = json_decode($snapshot);

        return new SnapshotEntity($snapshot->snapshot);
    }

    /**
     * @param string $id
     *
     * @throws HttpException
     *
     * @return void
     */
    public function delete($id)
    {
        $this->httpClient->delete(sprintf('%s/snapshots/%s', $this->endpoint, $id));
    }
}
