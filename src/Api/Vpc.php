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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Vpc as VpcEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Manuel Christlieb <manuel@gchristlieb.eu>
 */
class Vpc extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return VpcEntity[]
     */
    public function getAll(): array
    {
        $vpcs = $this->get('vpcs');

        return \array_map(static fn ($vpc) => new VpcEntity($vpc), $vpcs->vpcs);
    }
}
