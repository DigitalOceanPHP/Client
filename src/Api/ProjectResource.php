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

use DigitalOceanV2\Entity\ProjectResource as ProjectResourceEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Mohammad Salamat <godfather@mofia.org>
 */
class ProjectResource extends AbstractApi
{
    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return ProjectResourceEntity[]
     */
    public function getProjectResources(string $id)
    {
        $resources = $this->get(\sprintf('projects/%s/resources', $id));

        return \array_map(function ($resource) {
            return new ProjectResourceEntity($resource);
        }, $resources->resources);
    }

    /**
     * @param string        $id
     * @param array<string> $resources
     *
     * @throws ExceptionInterface
     *
     * @return ProjectResourceEntity[]
     */
    public function assignResources(string $id, array $resources)
    {
        $resources = $this->post(\sprintf('projects/%s/resources', $id), [
            'resources' => $resources,
        ]);

        return \array_map(function ($resource) {
            return new ProjectResourceEntity($resource);
        }, $resources->resources);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return ProjectResourceEntity[]
     */
    public function getDefaultProjectResources()
    {
        $resources = $this->get('projects/default/resources');

        return \array_map(function ($resource) {
            return new ProjectResourceEntity($resource);
        }, $resources->resources);
    }

    /**
     * @param array<string> $resources
     *
     * @throws ExceptionInterface
     *
     * @return ProjectResourceEntity[]
     */
    public function assignResourcesToDefaultProject(array $resources)
    {
        $resources = $this->post('projects/default/resources', [
            'resources' => $resources,
        ]);

        return \array_map(function ($resource) {
            return new ProjectResourceEntity($resource);
        }, $resources->resources);
    }
}
