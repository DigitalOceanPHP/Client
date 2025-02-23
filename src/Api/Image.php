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
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Image extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getAll(array $criteria = []): array
    {
        $query = [];

        if (isset($criteria['type']) && \in_array($criteria['type'], ['distribution', 'application'], true)) {
            $query['type'] = $criteria['type'];
        }

        if (isset($criteria['private']) && (bool) $criteria['private']) {
            $query['private'] = 'true';
        }

        $images = $this->get('images', $query);

        return \array_map(function ($image) {
            return new ImageEntity($image);
        }, $images->images);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(int $id): ImageEntity
    {
        $image = $this->get(\sprintf('images/%d', $id));

        return new ImageEntity($image->image);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getBySlug(string $slug): ImageEntity
    {
        $image = $this->get(\sprintf('images/%s', $slug));

        return new ImageEntity($image->image);
    }

    /**
     * @throws ExceptionInterface
     */
    public function update(int $id, string $name): ImageEntity
    {
        $image = $this->put(\sprintf('images/%d', $id), ['name' => $name]);

        return new ImageEntity($image->image);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(int $id): void
    {
        $this->delete(\sprintf('images/%d', $id));
    }

    /**
     * @throws ExceptionInterface
     */
    public function transfer(int $id, string $regionSlug): ActionEntity
    {
        $action = $this->post(\sprintf('images/%d/actions', $id), ['type' => 'transfer', 'region' => $regionSlug]);

        return new ActionEntity($action->action);
    }

    /**
     * @throws ExceptionInterface
     */
    public function convert(int $id): ActionEntity
    {
        $action = $this->post(\sprintf('images/%d/actions', $id), ['type' => 'convert']);

        return new ActionEntity($action->action);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getAction(int $id, int $actionId): ActionEntity
    {
        $action = $this->get(\sprintf('images/%d/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }
}
