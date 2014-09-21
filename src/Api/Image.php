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
use DigitalOceanV2\Entity\Image as ImageEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Image extends AbstractApi
{
    /**
     * @return ImageEntity[]
     */
    public function getAll()
    {
        $images = $this->adapter->get(sprintf('%s/images?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
        $images = json_decode($images);
        $this->extractMeta($images);

        return array_map(function ($image) {
                return new ImageEntity($image);
            }, $images->images);
    }

    /**
     * @param  integer     $id
     * @return ImageEntity
     */
    public function getById($id)
    {
        $image = $this->adapter->get(sprintf('%s/images/%d', self::ENDPOINT, $id));
        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param  string      $slug
     * @return ImageEntity
     */
    public function getBySlug($slug)
    {
        $image = $this->adapter->get(sprintf('%s/images/%s', self::ENDPOINT, $slug));
        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param  integer           $id
     * @param  string            $name
     * @throws \RuntimeException
     * @return ImageEntity
     */
    public function update($id, $name)
    {
        $headers = array('Content-Type: application/json');
        $content = sprintf('{"name":"%s"}', $name);

        $image = $this->adapter->put(sprintf('%s/images/%d', self::ENDPOINT, $id), $headers, $content);
        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param  integer           $id
     * @throws \RuntimeException
     */
    public function delete($id)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/images/%d', self::ENDPOINT, $id), $headers);
    }

    /**
     * @param  integer           $id
     * @param  string            $regionSlug
     * @throws \RuntimeException
     * @return ActionEntity
     */
    public function transfer($id, $regionSlug)
    {
        $headers = array('Content-Type: application/json');
        $content = sprintf('{"type":"transfer","region":"%s"}', $regionSlug);

        $action = $this->adapter->post(sprintf('%s/images/%d/actions', self::ENDPOINT, $id), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param  integer      $id
     * @param  integer      $actionId
     * @return ActionEntity
     */
    public function getAction($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/images/%d/actions/%d', self::ENDPOINT, $id, $actionId));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
