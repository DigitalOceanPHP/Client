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
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class Account extends AbstractEntity
{
    /**
     * @var int
     */
    public $dropletLimit;

    /**
     * @var int
     */
    public $floatingIpLimit;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $uuid;

    /**
     * @var bool
     */
    public $emailVerified;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $statusMessage;

    /**
     * @var Team
     */
    public $team;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'team':
                    if (\is_object($value)) {
                        $this->team = new Team($value);
                    }
                    unset($parameters[$property]);

                    break;
            }
        }

        parent::build($parameters);
    }
}
