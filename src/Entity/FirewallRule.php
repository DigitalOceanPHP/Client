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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class FirewallRule extends AbstractEntity
{
    /**
     * @var string
     */
    public $protocol;

    /**
     * @var string
     */
    public $ports;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'protocol' => $this->protocol,
        ];

        if ('icmp' != $this->protocol) {
            $data['ports'] = ('0' === $this->ports) ? 'all' : $this->ports;
        }

        return $data;
    }
}
