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
abstract class FirewallRule extends AbstractEntity
{
    public string $protocol;

    public string $ports;

    public ?string $action;

    /**
     * @psalm-suppress RedundantPropertyInitializationCheck
     */
    public function toArray(): array
    {
        $data = [
            'protocol' => $this->protocol,
        ];

        if ('icmp' !== $this->protocol && 'all' !== $this->protocol) {
            $data['ports'] = ('0' === $this->ports) ? 'all' : $this->ports;
        } elseif ('all' === $this->protocol && isset($this->ports)) {
            $data['ports'] = $this->ports;
        }

        if (isset($this->action)) {
            $data['action'] = $this->action;
        }

        return $data;
    }
}
