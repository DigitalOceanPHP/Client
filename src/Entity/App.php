<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
final class App extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $owner_uuid;

    /**
     * @var array
     */
    public $spec;

    /**
     * @var string
     */
    public $default_ingress;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var array
     */
    public $active_deployment;

    /**
     * @var array
     */
    public $in_progress_deployment;

    /**
     * @var string
     */
    public $last_deployment_created_at;

    /**
     * @var string
     */
    public $live_url;

    /**
     * @var array
     */
    public $region;

    /**
     * @var string
     */
    public $tier_slug;

    /**
     * @var string
     */
    public $live_url_base;

    /**
     * @var string
     */
    public $live_domain;

    /**
     * @var array
     */
    public $domains;
}
