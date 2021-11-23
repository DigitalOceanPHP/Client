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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class DomainRecord extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $data;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var int
     */
    public $port;

    /**
     * @var int
     */
    public $ttl;

    /**
     * @var int
     */
    public $weight;

    /**
     * @var int
     */
    public $flags;

    /**
     * @var string
     */
    public $tag;
}
