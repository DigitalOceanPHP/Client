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
final class DatabaseReplica extends AbstractEntity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var DatabaseConnection
     */
    public $connection;

    /**
     * @var DatabaseConnection
     */
    public $privateConnection;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $status;

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
