<?php

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
 * @author Antoine Corcy <contact@sbin.dk>
 */
class NextBackupWindow extends AbstractEntity
{
    /**
     * @var string
     */
    public $start;

    /**
     * @var string
     */
    public $end;

    /**
     * Build a new backup window instance
     *
     * @param \stdClass|array|null $parameters
     */
    public function build($parameters)
    {
        if (is_null($parameters)) {
            return;
        }

        parent::build($parameters);
    }
}
