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

use DigitalOceanV2\Entity\AbstractEntity;
use DigitalOceanV2\Entity\ForwardingRule as ForwardRuleEntity;
use DigitalOceanV2\Entity\HealthCheck as HealthCheckEntity;
use DigitalOceanV2\Entity\LoadBalancer as LoadBalancerEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Jacob Holmes <jwh315@cox.net>
 */
class LoadBalancer extends AbstractApi
{
    /**
     * @return LoadBalancerEntity[]
     */
    public function getAll()
    {
        $loadBalancers = $this->adapter->get(sprintf('%s/load_balancers', $this->endpoint));

        $loadBalancers = json_decode($loadBalancers);

        $this->extractMeta($loadBalancers);

        return array_map(function ($key) {
            return new LoadBalancerEntity($key);
        }, $loadBalancers->load_balancers);
    }

    /**
     * @param string $id
     *
     * @throws HttpException
     *
     * @return LoadBalancerEntity
     */
    public function getById($id)
    {
        $loadBalancer = $this->adapter->get(sprintf('%s/load_balancers/%s', $this->endpoint, $id));

        $loadBalancer = json_decode($loadBalancer);

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
     * @throws HttpException
     *
     * @return LoadBalancerEntity
     */
    public function create(
        $name,
        $region,
        $forwardRules = null,
        $algorithm = 'round_robin',
        $healthCheck = [],
        $stickySessions = [],
        $dropletIds = [],
        $httpsRedirect = false
    ) {
        $data = [
            'name' => $name,
            'algorithm' => $algorithm,
            'region' => $region,
            'forwarding_rules' => $this->formatForwardRules($forwardRules),
            'health_check' => $this->formatConfigurationOptions($healthCheck),
            'sticky_sessions' => $this->formatConfigurationOptions($stickySessions),
            'droplet_ids' => $dropletIds,
            'redirect_http_to_https' => $httpsRedirect,
        ];

        $loadBalancer = $this->adapter->post(sprintf('%s/load_balancers', $this->endpoint), $data);

        $loadBalancer = json_decode($loadBalancer);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param string                      $name
     * @param string                      $region
     * @param array|ForwardRuleEntity[]   $forwardRules
     * @param string                      $algorithm
     * @param array|HealthCheckEntity[]   $healthCheck
     * @param array|StickySessionEntity[] $stickySessions
     * @param bool                        $httpsRedirect
     * @param array                       $tags
     *
     * @throws HttpException
     *
     * @return LoadBalancerEntity
     */
    public function createTag(
        $name,
        $region,
        $forwardRules = null,
        $algorithm = 'round_robin',
        $healthCheck = [],
        $stickySessions = [],
        $httpsRedirect = false
        $tags = '',
    ) {
        $data = [
            'name' => $name,
            'algorithm' => $algorithm,
            'region' => $region,
            'forwarding_rules' => $this->formatForwardRules($forwardRules),
            'health_check' => $this->formatConfigurationOptions($healthCheck),
            'sticky_sessions' => $this->formatConfigurationOptions($stickySessions),
            'redirect_http_to_https' => $httpsRedirect,
            'tags' => $tags,
        ];

        $loadBalancer = $this->adapter->post(sprintf('%s/load_balancers', $this->endpoint), $data);

        $loadBalancer = json_decode($loadBalancer);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }
    
    /**
     * @param string                   $id
     * @param array|LoadBalancerEntity $loadBalancerSpec
     *
     * @throws HttpException
     *
     * @return LoadBalancerEntity
     */
    public function update($id, $loadBalancerSpec)
    {
        $data = $this->formatConfigurationOptions($loadBalancerSpec);

        $loadBalancer = $this->adapter->put(sprintf('%s/load_balancers/%s', $this->endpoint, $id), $data);

        $loadBalancer = json_decode($loadBalancer);

        return new LoadBalancerEntity($loadBalancer->load_balancer);
    }

    /**
     * @param string $id
     *
     * @throws HttpException
     */
    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/load_balancers/%s', $this->endpoint, $id));
    }

    /**
     * @param array|AbstractEntity $forwardRules
     *
     * @return array
     */
    private function formatForwardRules($forwardRules)
    {
        if (isset($forwardRules)) {
            return array_map(function ($rule) {
                return $this->formatConfigurationOptions($rule);
            }, $forwardRules);
        } else {
            return [
                (new ForwardRuleEntity())->setStandardHttpRules()->toArray(),
                (new ForwardRuleEntity())->setStandardHttpsRules()->toArray(),
            ];
        }
    }

    /**
     * @param array|AbstractEntity $config
     *
     * @return array|AbstractEntity
     */
    private function formatConfigurationOptions($config)
    {
        return $config instanceof AbstractEntity ? $config->toArray() : $config;
    }
}
