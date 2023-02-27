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
 * @author Mohammad Salamat <godfather@mofia.org>
 */
final class ProjectResource extends AbstractEntity
{
    /**
     * @var string
     */
    public $urn;

    /**
     * @var string
     */
    public $assignedAt;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $status;
}
