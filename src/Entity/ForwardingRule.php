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
 * @author Jacob Holmes <jwh315@cox.net>
 */
class ForwardingRule extends AbstractEntity
{
    public string $entryProtocol;

    public int $entryPort;

    public string $targetProtocol;

    public int $targetPort;

    public ?string $certificateId;

    public ?bool $tlsPassthrough;

    /**
     * @return $this
     */
    public function setStandardHttpRules(): self
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
    public function setStandardHttpsRules(): self
    {
        $this->entryProtocol = 'https';
        $this->targetProtocol = 'https';
        $this->entryPort = 443;
        $this->targetPort = 443;
        $this->tlsPassthrough = true;

        return $this;
    }
}
