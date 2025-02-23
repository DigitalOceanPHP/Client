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

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\ForwardingRule as ForwardRuleEntity;
use DigitalOceanV2\Entity\HealthCheck as HealthCheckEntity;
use DigitalOceanV2\Entity\LoadBalancer as LoadBalancerEntity;
use DigitalOceanV2\Entity\StickySession as StickySessionEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Jacob Holmes <jwh315@cox.net>
 */
class LoadBalancer extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return LoadBalancerEntity[]
     */
    public function getAll(): array
    {
        $loadBalancers = $this->get('load_balancers');

        return \array_map(function ($key) {
            return new LoadBalancerEntity($key);
        }, $loadBalancers->load_balancers);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(string $id): LoadBalancerEntity
    {
        $loadBalancer = $this->get(\sprintf('load_balancers/%s', $id));

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param array|ForwardRuleEntity[]   $forwardRules
     * @param array|HealthCheckEntity[]   $healthCheck
     * @param array|StickySessionEntity[] $stickySessions
     * @param int<30, 600>                $httpIdleTimeoutSeconds
     *
     * @throws ExceptionInterface
     */
    public function create(
        string $name,
        string $region,
        ?array $forwardRules = null,
        string $algorithm = 'round_robin',
        array $healthCheck = [],
        array $stickySessions = [],
        array $dropletIds = [],
        bool $httpsRedirect = false,
        int $httpIdleTimeoutSeconds = 60
    ): LoadBalancerEntity {
        $loadBalancer = $this->post('load_balancers', [
            'name' => $name,
            'algorithm' => $algorithm,
            'region' => $region,
            'forwarding_rules' => null === $forwardRules ? null : self::formatForwardRules($forwardRules),
            'health_check' => self::formatConfigurationOptions($healthCheck),
            'sticky_sessions' => self::formatConfigurationOptions($stickySessions),
            'droplet_ids' => $dropletIds,
            'redirect_http_to_https' => $httpsRedirect,
            'http_idle_timeout_seconds' => $httpIdleTimeoutSeconds,
        ]);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @throws ExceptionInterface
     */
    public function update(string $id, array|LoadBalancerEntity $loadBalancerSpec): LoadBalancerEntity
    {
        $data = self::formatConfigurationOptions($loadBalancerSpec);

        $loadBalancer = $this->put(\sprintf('load_balancers/%s', $id), $data);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('load_balancers/%s', $id));
    }

    private static function formatForwardRules(array|AbstractEntity $forwardRules): array
    {
        if (\is_array($forwardRules)) {
            return \array_map(function ($rule) {
                return self::formatConfigurationOptions($rule);
            }, $forwardRules);
        }

        return [
            (new ForwardRuleEntity())->setStandardHttpRules()->toArray(),
            (new ForwardRuleEntity())->setStandardHttpsRules()->toArray(),
        ];
    }

    private static function formatConfigurationOptions(array|AbstractEntity $config): array
    {
        return $config instanceof AbstractEntity ? $config->toArray() : $config;
    }
}
