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

use DigitalOceanV2\Entity\App as AppEntity;
use DigitalOceanV2\Entity\AppDeployment as AppDeploymentEntity;
use DigitalOceanV2\Entity\AppDeploymentLog as AppDeploymentLogEntity;
use DigitalOceanV2\Entity\AppInstanceSize as AppInstanceSizeEntity;
use DigitalOceanV2\Entity\AppRegion as AppRegionEntity;
use DigitalOceanV2\Entity\AppTier as AppTierEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class App extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return AppEntity[]
     */
    public function getAll(): array
    {
        $apps = $this->get('apps');

        return \array_map(function ($app) {
            return new AppEntity($app);
        }, $apps->apps ?? []);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getByID(string $appID): AppEntity
    {
        $app = $this->get(\sprintf('apps/%s', $appID));

        return new AppEntity($app->app);
    }

    /**
     * @throws ExceptionInterface
     */
    public function create(array $spec): AppEntity
    {
        $app = $this->post('apps', [
            'spec' => $spec,
        ]);

        return new AppEntity($app->app);
    }

    /**
     * @throws ExceptionInterface
     */
    public function update(string $appID, array $spec): AppEntity
    {
        $result = $this->put(\sprintf('apps/%s', $appID), [
            'spec' => $spec,
        ]);

        return new AppEntity($result->app);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $appID): void
    {
        $this->delete(\sprintf('apps/%s', $appID));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppDeploymentEntity[]
     */
    public function getAppDeployments(string $appID): array
    {
        $deployments = $this->get(\sprintf('apps/%s/deployments', $appID));

        return \array_map(function ($deployment) {
            return new AppDeploymentEntity($deployment);
        }, $deployments->deployments);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getAppDeployment(string $appID, string $deploymentID): AppDeploymentEntity
    {
        $deployment = $this->get(\sprintf('apps/%s/deployments/%s', $appID, $deploymentID));

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createAppDeployment(string $appID, bool $force_build = true): AppDeploymentEntity
    {
        $deployment = $this->post(\sprintf('apps/%s/deployments', $appID), [
            'force_build' => $force_build,
        ]);

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @throws ExceptionInterface
     */
    public function cancelAppDeployment(string $appID, string $deploymentID): AppDeploymentEntity
    {
        $deployment = $this->post(\sprintf('apps/%s/deployments/%s/cancel', $appID, $deploymentID));

        return new AppDeploymentEntity($deployment->deployment);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getDeploymentLogs(string $appID, string $deploymentID, string $componentName): AppDeploymentLogEntity
    {
        $logs = $this->get(\sprintf('apps/%s/deployments/%s/components/%s/logs', $appID, $deploymentID, $componentName));

        return new AppDeploymentLogEntity($logs);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getAggregateDeploymentLogs(string $appID, string $deploymentID): AppDeploymentLogEntity
    {
        $logs = $this->get(\sprintf('apps/%s/deployments/%s/logs', $appID, $deploymentID));

        return new AppDeploymentLogEntity($logs);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppRegionEntity[]
     */
    public function getRegions(): array
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
    public function getTiers(): array
    {
        $tiers = $this->get('apps/tiers');

        return \array_map(function ($tier) {
            return new AppTierEntity($tier);
        }, $tiers->tiers);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getTierBySlug(string $slug): AppTierEntity
    {
        $tier = $this->get(\sprintf('apps/tiers/%s', $slug));

        return new AppTierEntity($tier);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return AppInstanceSizeEntity[]
     */
    public function getInstanceSizes(): array
    {
        $instance_sizes = $this->get('apps/tiers/instance_sizes');

        return \array_map(function ($instance_size) {
            return new AppInstanceSizeEntity($instance_size);
        }, $instance_sizes->instance_sizes);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getInstanceSizeBySlug(string $slug): AppInstanceSizeEntity
    {
        $instance_size = $this->get(\sprintf('apps/tiers/instance_sizes/%s', $slug));

        return new AppInstanceSizeEntity($instance_size);
    }
}
