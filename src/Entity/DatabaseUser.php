<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
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
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $password;

    /**
     * @var MysqlSettings
     */
    public $mysqlSettings;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters)
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('mysql_settings' === $property && \is_object($value)) {
                $this->mysqlSettings = new MysqlSettings($value);
            }
        }
    }
}
