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

    public string $ipv6;

    public string $projectId;

    public int $sizeUnit;

    public string $size;

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

    public bool $enableProxyProtocol;

    public bool $enableBackendKeepalive;

    /**
     * @var int<30, 600>
     */
    public int $httpIdleTimeoutSeconds;

    public string $vpcUuid;

    public bool $disableLetsEncryptDnsRecords;

    public array $firewall;

    public string $network;

    public string $networkStack;

    public string $type;

    public array $domains;

    public array $glbSettings;

    public array $targetLoadBalancerIds;

    public string $tlsCipherPolicy;

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);

            if ('forwardingRules' === $property) {
                $this->forwardingRules = \array_map(fn ($v) => new ForwardingRule($v), $value);
                continue;
            } elseif ('healthCheck' === $property) {
                $this->healthCheck = new HealthCheck($value);
                continue;
            } elseif ('stickySessions' === $property) {
                $this->stickySessions = new StickySession($value);
                continue;
            } elseif ('region' === $property) {
                $this->region = new Region($value);
                continue;
            } elseif (
                \in_array($property, ['firewall', 'domains', 'glbSettings', 'targetLoadBalancerIds'], true) &&
                ($value instanceof \stdClass || \is_array($value))
            ) {
                $value = self::normalizeArray($value);
            }

            if (\property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function toArray(): array
    {
        $payload = parent::toArray();

        unset($payload['id'], $payload['ip'], $payload['ipv6'], $payload['status'], $payload['created_at']);

        if (isset($payload['region']) && $payload['region'] instanceof Region) {
            $payload['region'] = $payload['region']->slug;
        }

        if (isset($payload['forwarding_rules']) && \is_array($payload['forwarding_rules'])) {
            /** @var ForwardingRule[] $forwardingRules */
            $forwardingRules = $payload['forwarding_rules'];
            $payload['forwarding_rules'] = \array_map(function (ForwardingRule $rule): array {
                return $rule->toArray();
            }, $forwardingRules);
        }

        if (isset($payload['health_check']) && $payload['health_check'] instanceof HealthCheck) {
            $payload['health_check'] = $payload['health_check']->toArray();
        }

        if (isset($payload['sticky_sessions']) && $payload['sticky_sessions'] instanceof StickySession) {
            $payload['sticky_sessions'] = $payload['sticky_sessions']->toArray();
        }

        if (isset($payload['tag']) && '' !== $payload['tag']) {
            unset($payload['droplet_ids']);
        } else {
            unset($payload['tag']);
        }

        $data = [];

        foreach ([
            'name',
            'region',
            'algorithm',
            'size_unit',
            'size',
            'forwarding_rules',
            'health_check',
            'sticky_sessions',
            'tag',
            'droplet_ids',
            'redirect_http_to_https',
            'enable_proxy_protocol',
            'enable_backend_keepalive',
            'http_idle_timeout_seconds',
            'vpc_uuid',
            'disable_lets_encrypt_dns_records',
            'project_id',
            'firewall',
            'network',
            'network_stack',
            'type',
            'domains',
            'glb_settings',
            'target_load_balancer_ids',
            'tls_cipher_policy',
        ] as $property) {
            if (\array_key_exists($property, $payload)) {
                $data[$property] = $payload[$property];
            }
        }

        return $data;
    }

    private static function normalizeArray(array|\stdClass $value): array
    {
        if ($value instanceof \stdClass) {
            $value = \get_object_vars($value);
        }

        foreach ($value as $key => $subValue) {
            if ($subValue instanceof \stdClass || \is_array($subValue)) {
                $value[$key] = self::normalizeArray($subValue);
            }
        }

        return $value;
    }
}
