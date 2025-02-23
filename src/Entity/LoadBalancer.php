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

namespace DigitalOceanV2\Entity;

/**
 * @author Jacob Holmes <jwh315@cox.net>
 */
final class LoadBalancer extends AbstractEntity
{
    public string $id;

    public int $name;

    public string $ip;

    public string $algorithm;

    public string $status;

    public string $createdAt;

    /**
     * @var ForwardingRule[]
     */
    public array $forwardingRules;

    public HealthCheck $healthCheck;

    public StickySession $stickySessions;

    public Region $region;

    public string $tag;

    public array $dropletIds;

    public bool $redirectHttpToHttps;

    /**
     * @var int<30, 600>
     */
    public int $httpIdleTimeoutSeconds;

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'forwarding_rules':
                    foreach ($value as $forwardingRule) {
                        $this->forwardingRules[] = new ForwardingRule($forwardingRule);
                    }

                    unset($parameters[$property]);

                    break;

                case 'health_check':
                    if (\is_object($value)) {
                        $this->healthCheck = new HealthCheck($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'sticky_sessions':
                    if (\is_object($value)) {
                        $this->stickySessions = new StickySession($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'region':
                    if (\is_object($value)) {
                        $this->region = new Region($value);
                    }
                    unset($parameters[$property]);

                    break;
            }
        }

        parent::build($parameters);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'region' => $this->region->slug,
            'algorithm' => $this->algorithm,
            'forwarding_rules' => \array_map(function ($rule): array {
                return $rule->toArray();
            }, $this->forwardingRules),
            'health_check' => $this->healthCheck->toArray(),
            'sticky_sessions' => $this->stickySessions->toArray(),
            'droplet_ids' => $this->dropletIds,
            'redirect_http_to_https' => $this->redirectHttpToHttps,
            'http_idle_timeout_seconds' => $this->httpIdleTimeoutSeconds,
        ];
    }
}
