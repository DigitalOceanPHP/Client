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
    public function getAll()
    {
        $loadBalancers = $this->get('load_balancers');

        return \array_map(function ($key) {
            return new LoadBalancerEntity($key);
        }, $loadBalancers->load_balancers);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return LoadBalancerEntity
     */
    public function getById(string $id)
    {
        $loadBalancer = $this->get(\sprintf('load_balancers/%s', $id));

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param string                      $name
     * @param string                      $region
     * @param array|ForwardRuleEntity[]   $forwardRules
     * @param string                      $algorithm
     * @param array|HealthCheckEntity[]   $healthCheck
     * @param array|StickySessionEntity[] $stickySessions
     * @param array                       $dropletIds
     * @param bool                        $httpsRedirect
     *
     * @throws ExceptionInterface
     *
     * @return LoadBalancerEntity
     */
    public function create(
        string $name,
        string $region,
        array $forwardRules = null,
        string $algorithm = 'round_robin',
        array $healthCheck = [],
        array $stickySessions = [],
        array $dropletIds = [],
        bool $httpsRedirect = false
    ) {
        $loadBalancer = $this->post('load_balancers', [
            'name' => $name,
            'algorithm' => $algorithm,
            'region' => $region,
            'forwarding_rules' => null === $forwardRules ? null : self::formatForwardRules($forwardRules),
            'health_check' => self::formatConfigurationOptions($healthCheck),
            'sticky_sessions' => self::formatConfigurationOptions($stickySessions),
            'droplet_ids' => $dropletIds,
            'redirect_http_to_https' => $httpsRedirect,
        ]);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param string                   $id
     * @param array|LoadBalancerEntity $loadBalancerSpec
     *
     * @throws ExceptionInterface
     *
     * @return LoadBalancerEntity
     */
    public function update(string $id, $loadBalancerSpec)
    {
        $data = self::formatConfigurationOptions($loadBalancerSpec);

        $loadBalancer = $this->put(\sprintf('load_balancers/%s', $id), $data);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('load_balancers/%s', $id));
    }

    /**
     * @param array|AbstractEntity $forwardRules
     *
     * @return array
     */
    private static function formatForwardRules($forwardRules)
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

    /**
     * @param array|AbstractEntity $config
     *
     * @return array
     */
    private static function formatConfigurationOptions($config)
    {
        return $config instanceof AbstractEntity ? $config->toArray() : $config;
    }
}
