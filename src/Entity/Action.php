<?php

/**
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
class Action extends AbstractEntity
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $startedAt;

    /**
     * @var string
     */
    public $completedAt;

    /**
     * @var string
     */
    public $resourceId;

    /**
     * @var string
     */
    public $resourceType;

    /**
     * @var string
     */
    public $region;

    /**
     * @param string $completedAt
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $this->convertDateTime($completedAt);
    }

    /**
     * @param string $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $this->convertDateTime($startedAt);
    }
}
