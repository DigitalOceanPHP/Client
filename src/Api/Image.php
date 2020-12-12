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
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Image extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity[]
     */
    public function getAll(array $criteria = [])
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
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity
     */
    public function getById(int $id)
    {
        $image = $this->get(\sprintf('images/%d', $id));

        return new ImageEntity($image->image);
    }

    /**
     * @param string $slug
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity
     */
    public function getBySlug(string $slug)
    {
        $image = $this->get(\sprintf('images/%s', $slug));

        return new ImageEntity($image->image);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return ImageEntity
     */
    public function update(int $id, string $name)
    {
        $image = $this->put(\sprintf('images/%d', $id), ['name' => $name]);

        return new ImageEntity($image->image);
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
        $this->delete(\sprintf('images/%d', $id));
    }

    /**
     * @param int    $id
     * @param string $regionSlug
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function transfer(int $id, string $regionSlug)
    {
        $action = $this->post(\sprintf('images/%d/actions', $id), ['type' => 'transfer', 'region' => $regionSlug]);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function convert(int $id)
    {
        $action = $this->post(\sprintf('images/%d/actions', $id), ['type' => 'convert']);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getAction(int $id, int $actionId)
    {
        $action = $this->get(\sprintf('images/%d/actions/%d', $id, $actionId));

        return new ActionEntity($action->action);
    }
}
