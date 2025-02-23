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
    public function getAll(): array
    {
        $tags = $this->get('tags');

        return \array_map(function ($tag) {
            return new TagEntity($tag);
        }, $tags->tags);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getByName(string $name): TagEntity
    {
        $tag = $this->get(\sprintf('tags/%s', $name));

        return new TagEntity($tag->tag);
    }

    /**
     * @throws ExceptionInterface
     */
    public function create(string $name): TagEntity
    {
        $tag = $this->post('tags', ['name' => $name]);

        return new TagEntity($tag->tag);
    }

    /**
     * @throws ExceptionInterface
     */
    public function tagResources(string $name, array $resources): void
    {
        $this->post(\sprintf('tags/%s/resources', $name), ['resources' => $resources]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function untagResources(string $name, array $resources): void
    {
        $this->delete(\sprintf('tags/%s/resources', $name), ['resources' => $resources]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $name): void
    {
        $this->delete(\sprintf('tags/%s', $name));
    }
}
