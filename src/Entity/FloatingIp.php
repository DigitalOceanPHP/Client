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
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class FloatingIp extends AbstractEntity
{
    /**
     * @var string
     */
    public $ip;

    /**
     * @var Droplet|null
     */
    public $droplet;

    /**
     * @var Region
     */
    public $region;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        parent::build($parameters);

        foreach ($parameters as $property => $value) {
            if ('droplet' === $property && \is_object($value)) {
                $this->droplet = new Droplet($value);
            }

            if ('region' === $property && \is_object($value)) {
                $this->region = new Region($value);
            }
        }
    }
}
