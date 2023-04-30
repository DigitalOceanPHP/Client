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
 * @author Manuel Christlieb <manuel@christlieb.eu>
 */
final class ReservedIp extends AbstractEntity
{
    public string $ip;

    public ?Droplet $droplet = null;

    public Region $region;
    public bool $locked;
    public string $projectId;

    public function build(array $parameters): void
    {
        if (isset($parameters['droplet'])) {
            $this->droplet = new Droplet($parameters['droplet']);
            unset($parameters['droplet']);
        }
        $this->region = new Region($parameters['region']);
        unset($parameters['region']);
        parent::build($parameters);
    }
}
