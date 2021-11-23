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

use DigitalOceanV2\Entity\Size as SizeEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Size extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return SizeEntity[]
     */
    public function getAll()
    {
        $sizes = $this->get('sizes');

        return \array_map(function ($size) {
            return new SizeEntity($size);
        }, $sizes->sizes);
    }
}
