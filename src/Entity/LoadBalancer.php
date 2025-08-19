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

    public string $name;

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
            $property = static::convertToCamelCase($property);

            if ('forwardingRules' === $property) {
                $this->forwardingRules = \array_map(fn ($v) => new ForwardingRule($v), $value);
            } elseif ('healthCheck' === $property) {
                $this->healthCheck = new HealthCheck($value);
            } elseif ('stickySessions' === $property) {
                $this->stickySessions = new StickySession($value);
            } elseif ('region' === $property) {
                $this->region = new Region($value);
            } elseif (\property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
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
