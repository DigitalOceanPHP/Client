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
final class DatabaseUser extends AbstractEntity
{
    public string $name;

    public string $role;

    public string $password;

    public DatabaseMysqlSettings $mysqlSettings;

    public function build(array $parameters): void
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('mysql_settings' === $property && \is_object($value)) {
                $this->mysqlSettings = new DatabaseMysqlSettings($value);
            }
        }
    }
}
