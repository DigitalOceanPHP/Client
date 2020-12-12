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
 * @author Jacob Holmes <jwh315@cox.net>
 */
class ForwardingRule extends AbstractEntity
{
    /**
     * @var string
     */
    public $entryProtocol;

    /**
     * @var int
     */
    public $entryPort;

    /**
     * @var string
     */
    public $targetProtocol;

    /**
     * @var int
     */
    public $targetPort;

    /**
     * @var string|null
     */
    public $certificateId;

    /**
     * @var bool|null
     */
    public $tlsPassthrough;

    /**
     * @return $this
     */
    public function setStandardHttpRules()
    {
        $this->entryProtocol = 'http';
        $this->targetProtocol = 'http';
        $this->entryPort = 80;
        $this->targetPort = 80;

        return $this;
    }

    /**
     * @return $this
     */
    public function setStandardHttpsRules()
    {
        $this->entryProtocol = 'https';
        $this->targetProtocol = 'https';
        $this->entryPort = 443;
        $this->targetPort = 443;
        $this->tlsPassthrough = true;

        return $this;
    }
}
