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
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
final class DatabaseCluster extends AbstractEntity
{
    public string $id;

    public string $name;

    public string $engine;

    public string $version;

    public DatabaseConnection $connection;

    public DatabaseConnection $privateConnection;

    /**
     * @var DatabaseUser[]
     */
    public array $users = [];

    /**
     * @var string[]
     */
    public array $dbNames = [];

    public int $numNodes;

    public string $size;

    public string $region;

    public string $status;

    public DatabaseMaintenanceWindow $maintenanceWindow;

    public string $createdAt;

    /**
     * @var string[]
     */
    public array $tags = [];

    public string $privateNetworkUuid;

    public ?int $storageSizeMib;

    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);

            if ('connection' === $property) {
                $this->connection = new DatabaseConnection($value);
            } elseif ('privateConnection' === $property) {
                $this->privateConnection = new DatabaseConnection($value);
            } elseif ('users' === $property) {
                $this->users = \array_map(fn ($v) => new DatabaseUser($v), $value ?? []);
            } elseif ('maintenanceWindow' === $property) {
                $this->maintenanceWindow = new DatabaseMaintenanceWindow($value);
            } elseif('dbNames' === $property) {
                $this->dbNames = $value ?? [];
            } elseif (\property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
