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

namespace DigitalOceanV2\Entity;

/**
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
final class DatabaseCluster extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $engine;

    /**
     * @var string
     */
    public $version;

    /**
     * @var DatabaseConnection
     */
    public $connection;

    /**
     * @var DatabaseConnection
     */
    public $privateConnection;

    /**
     * @var DatabaseUser[]
     */
    public $users = [];

    /**
     * @var string[]
     */
    public $dbNames = [];

    /**
     * @var int
     */
    public $numNodes;

    /**
     * @var string
     */
    public $size;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $status;

    /**
     * @var DatabaseMaintenanceWindow
     */
    public $maintenanceWindow;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string[]
     */
    public $tags = [];

    /**
     * @var string
     */
    public $privateNetworkUuid;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('connection' === $property && \is_object($value)) {
                $this->connection = new DatabaseConnection($value);
            }

            if ('private_connection' === $property && \is_object($value)) {
                $this->privateConnection = new DatabaseConnection($value);
            }

            if ('users' === $property && \is_array($value)) {
                $this->users = [];
                foreach ($value as $user) {
                    if (\is_object($user)) {
                        $this->users[] = new DatabaseUser($user);
                    }
                }
            }

            if ('maintenance_window' === $property && \is_object($value)) {
                $this->maintenanceWindow = new DatabaseMaintenanceWindow($value);
            }
        }
    }

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
