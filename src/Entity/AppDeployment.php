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
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class AppDeployment extends AbstractEntity
{
    public string $id;

    public array $spec;

    public array $services;

    public array $staticSites;

    public array $workers;

    public array $jobs;

    public string $phaseLastUpdatedAt;

    public string $createdAt;

    public string $updatedAt;

    public string $cause;

    public string $clonedFrom;

    public array $progress;

    public string $phase;

    public string $tierSlug;

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            if (
                \in_array(static::convertToCamelCase($property), ['spec', 'services', 'staticSites', 'workers', 'jobs', 'progress'], true) &&
                ($value instanceof \stdClass || \is_array($value))
            ) {
                $parameters[$property] = self::normalizeArray($value);
            }
        }

        parent::build($parameters);
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
