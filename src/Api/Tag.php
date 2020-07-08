<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Tag as TagEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Nicolas Beauvais <nicolas@bvs.email>
 */
class Tag extends AbstractApi
{
    /**
     * @return TagEntity[]
     */
    public function getAll()
    {
        $tags = $this->httpClient->get(sprintf('%s/tags', $this->endpoint));

        $tags = json_decode($tags);

        return array_map(function ($tag) {
            return new TagEntity($tag);
        }, $tags->tags);
    }

    /**
     * @param string $name
     *
     * @return TagEntity
     */
    public function getByName($name)
    {
        $tag = $this->httpClient->get(sprintf('%s/tags/%s', $this->endpoint, $name));

        $tag = json_decode($tag);

        return new TagEntity($tag->tag);
    }

    /**
     * @param string $name
     *
     * @throws HttpException
     *
     * @return TagEntity
     */
    public function create($name)
    {
        $tag = $this->httpClient->post(sprintf('%s/tags', $this->endpoint), ['name' => $name]);

        $tag = json_decode($tag);

        return new TagEntity($tag->tag);
    }

    /**
     * @param string $name
     * @param array $resources
     *
     * @throws HttpException
     */
    public function tagResources($name, $resources)
    {
        $this->httpClient->post(sprintf('%s/tags/%s/resources', $this->endpoint, $name), ['resources' => $resources]);
    }

    /**
     * @param string $name
     * @param array $resources
     *
     * @throws HttpException
     */
    public function untagResources($name, $resources)
    {
        $this->httpClient->delete(sprintf('%s/tags/%s/resources', $this->endpoint, $name), ['resources' => $resources]);
    }

    /**
     * @param string $name
     *
     * @throws HttpException
     */
    public function delete($name)
    {
        $this->httpClient->delete(sprintf('%s/tags/%s', $this->endpoint, $name));
    }
}
