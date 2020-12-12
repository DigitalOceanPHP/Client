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

use DigitalOceanV2\Entity\Tag as TagEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Nicolas Beauvais <nicolas@bvs.email>
 */
class Tag extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return TagEntity[]
     */
    public function getAll()
    {
        $tags = $this->get('tags');

        return \array_map(function ($tag) {
            return new TagEntity($tag);
        }, $tags->tags);
    }

    /**
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return TagEntity
     */
    public function getByName(string $name)
    {
        $tag = $this->get(\sprintf('tags/%s', $name));

        return new TagEntity($tag->tag);
    }

    /**
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return TagEntity
     */
    public function create(string $name)
    {
        $tag = $this->post('tags', ['name' => $name]);

        return new TagEntity($tag->tag);
    }

    /**
     * @param string $name
     * @param array  $resources
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function tagResources(string $name, array $resources): void
    {
        $this->post(\sprintf('tags/%s/resources', $name), ['resources' => $resources]);
    }

    /**
     * @param string $name
     * @param array  $resources
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function untagResources(string $name, array $resources): void
    {
        $this->delete(\sprintf('tags/%s/resources', $name), ['resources' => $resources]);
    }

    /**
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $name): void
    {
        $this->delete(\sprintf('tags/%s', $name));
    }
}
