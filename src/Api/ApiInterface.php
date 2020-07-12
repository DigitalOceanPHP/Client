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

namespace DigitalOceanV2\Api;

interface ApiInterface
{
    /**
     * Create a new instance with the given per page parameter.
     *
     * This must be an integer between 1 and 200.
     *
     * @param int|null $perPage
     *
     * @return static
     */
    public function perPage(?int $perPage);

    /**
     * Create a new instance with the given page parameter.
     *
     * This must be an integer greater than or equal to 1.
     *
     * @param int|null $page
     *
     * @return static
     */
    public function page(?int $page);
}
