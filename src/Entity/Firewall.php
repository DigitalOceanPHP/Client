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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Firewall extends AbstractEntity
{
    public string $id;

    public string $status;

    public string $createdAt;

    public array $pendingChanges;

    public string $name;

    public array $inboundRules;

    public array $outboundRules;

    public array $dropletIds;

    public array $tags;

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'inbound_rules':
                    if (\is_array($value)) {
                        $this->inboundRules = [];
                        foreach ($value as $key => $rule) {
                            if (\is_object($rule)) {
                                $this->inboundRules[$key] = new FirewallRuleInbound($rule);
                            }
                        }
                    }
                    unset($parameters[$property]);

                    break;

                case 'outbound_rules':
                    if (\is_array($value)) {
                        $this->outboundRules = [];
                        foreach ($value as $key => $rule) {
                            if (\is_object($rule)) {
                                $this->outboundRules[$key] = new FirewallRuleOutbound($rule);
                            }
                        }
                    }
                    unset($parameters[$property]);

                    break;
            }
        }

        parent::build($parameters);
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'inbound_rules' => \array_map(function ($rule): array {
                return $rule->toArray();
            }, $this->inboundRules),
            'outbound_rules' => \array_map(function ($rule): array {
                return $rule->toArray();
            }, $this->outboundRules),
            'droplet_ids' => $this->dropletIds,
            'tags' => $this->tags,
        ];
    }
}
