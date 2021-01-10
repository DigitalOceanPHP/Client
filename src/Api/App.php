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

use DigitalOceanV2\Entity\App as AppEntity;
use DigitalOceanV2\Entity\AppDeployment as AppDeploymentEntity;
use DigitalOceanV2\Entity\AppDeploymentLog as AppDeploymentLogEntity;
use DigitalOceanV2\Entity\AppInstanceSize as AppInstanceSizeEntity;
use DigitalOceanV2\Entity\AppRegion as AppRegionEntity;
use DigitalOceanV2\Entity\AppTier as AppTierEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class App extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return AppEntity[]
     */
    public function getAll()
    {
        $apps = $this->get('apps');

        return \array_map(function ($app) {
            return new AppEntity($app);
        }, $apps->apps);
    }

    /**
     * @param string $appID
     *
     * @throws ExceptionInterface
     *
     * @return AppEntity
     */
    public function getByID(string $appID)
    {
        $app = $this->get(\sprintf('apps/%s', $appID));

        return new AppEntity($app->app);
    }

    /**
     * @param array $spec
     *
     * @throws ExceptionInterface
     *
     * @return AppEntity
     */
    public function create(array $spec)
    {
        $app = $this->post('apps', [
            'spec' => $spec,
        ]);

        return new AppEntity($app->app);
    }

    /**
     * @param string $appID
     * @param array  $spec
     *
     * @throws ExceptionInterface
     *
     * @return AppEntity
     */
    public function update(string $appID, array $spec)
    {
        $result = $this->put(\sprintf('apps/%s', $appID), [
            'spec' => $spec,
        ]);

        return new AppEntity($result->app);
    }

    /**
     * @param string $appID
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $appID): void
    {
        $this->delete(\sprintf('apps/%s', $appID));
    }

    /**
     * @param string $appID
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentEntity[]
     */
    public function getAppDeployments(string $appID)
    {
        $deployments = $this->get(\sprintf('apps/%s/deployments', $appID));

        return \array_map(function ($deployment) {
            return new AppDeploymentEntity($deployment);
        }, $deployments->deployments);
    }

    /**
     * @param string $deploymentID
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentEntity
     */
    public function getAppDeployment(string $appID, string $deploymentID)
    {
        $deployment = $this->get(\sprintf('apps/%s/deployments/%s', $appID, $deploymentID));

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @param string $appID
     * @param bool   $force_build
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentEntity
     */
    public function createAppDeployment(string $appID, $force_build = true)
    {
        $deployment = $this->post(\sprintf('apps/%s/deployments', $appID), [
            'force_build' => $force_build,
        ]);

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @param string $appID
     * @param string $deploymentID
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentEntity
     */
    public function cancelAppDeployment(string $appID, string $deploymentID)
    {
        $deployment = $this->post(\sprintf('apps/%s/deployments/%s/cancel', $appID, $deploymentID));

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @param string $appID
     * @param string $deploymentID
     * @param string $componentName
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentLogEntity
     */
    public function getDeploymentLogs(string $appID, string $deploymentID, string $componentName)
    {
        $logs = $this->get(\sprintf('apps/%s/deployments/%s/components/%s/logs', $appID, $deploymentID, $componentName));

        return new AppDeploymentLogEntity($logs);
    }

    /**
     * @param string $appID
     * @param string $deploymentID
     *
     * @throws ExceptionInterface
     *
     * @return AppDeploymentLogEntity
     */
    public function getAggregateDeploymentLogs(string $appID, string $deploymentID)
    {
        $logs = $this->get(\sprintf('apps/%s/deployments/%s/logs', $appID, $deploymentID));

        return new AppDeploymentLogEntity($logs);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppRegionEntity[]
     */
    public function getRegions()
    {
        $regions = $this->get('apps/regions');

        return \array_map(function ($region) {
            return new AppRegionEntity($region);
        }, $regions->regions);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppTierEntity[]
     */
    public function getTiers()
    {
        $tiers = $this->get('apps/tiers');

        return \array_map(function ($tier) {
            return new AppTierEntity($tier);
        }, $tiers->tiers);
    }

    /**
     * @param string $slug
     *
     * @throws ExceptionInterface
     *
     * @return AppTierEntity
     */
    public function getTierBySlug(string $slug)
    {
        $tier = $this->get(\sprintf('apps/tiers/%s', $slug));

        return new AppTierEntity($tier);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppInstanceSizeEntity[]
     */
    public function getInstanceSizes()
    {
        $instance_sizes = $this->get('apps/tiers/instance_sizes');

        return \array_map(function ($instance_size) {
            return new AppInstanceSizeEntity($instance_size);
        }, $instance_sizes->instance_sizes);
    }

    /**
     * @param string $slug
     *
     * @throws ExceptionInterface
     *
     * @return AppInstanceSizeEntity
     */
    public function getInstanceSizeBySlug(string $slug)
    {
        $instance_size = $this->get(\sprintf('apps/tiers/instance_sizes/%s', $slug));

        return new AppInstanceSizeEntity($instance_size);
    }
}
