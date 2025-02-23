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
final class DatabasePool extends AbstractEntity
{
    public string $name;

    public string $mode;

    public int $size;

    public string $db;

    public string $user;

    public DatabaseConnection $connection;

    public DatabaseConnection $privateConnection;

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
}
