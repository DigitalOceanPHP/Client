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
final class Droplet extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $memory;

    /**
     * @var int
     */
    public $vcpus;

    /**
     * @var int
     */
    public $disk;

    /**
     * @var Region
     */
    public $region;

    /**
     * @var Image
     */
    public $image;

    /**
     * @var Kernel
     */
    public $kernel;

    /**
     * @var Size
     */
    public $size;

    /**
     * @var string
     */
    public $sizeSlug;

    /**
     * @var bool
     */
    public $locked;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $status;

    /**
     * @var Tag[]
     */
    public $tags = [];

    /**
     * @var Network[]
     */
    public $networks = [];

    /**
     * @var int[]
     */
    public $backupIds = [];

    /**
     * @var string[]
     */
    public $volumeIds = [];

    /**
     * @var int[]
     */
    public $snapshotIds = [];

    /**
     * @var string[]
     */
    public $features = [];

    /**
     * @var bool
     */
    public $backupsEnabled;

    /**
     * @var bool
     */
    public $privateNetworkingEnabled;

    /**
     * @var bool
     */
    public $ipv6Enabled;

    /**
     * @var bool
     */
    public $virtIOEnabled;

    /**
     * @var NextBackupWindow
     */
    public $nextBackupWindow;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'networks':
                    if (\is_object($value)) {
                        if (\property_exists($value, 'v4')) {
                            foreach ($value->v4 as $subValue) {
                                $subValue->version = 4;
                                $this->networks[] = new Network($subValue);
                            }
                        }

                        if (\property_exists($value, 'v6')) {
                            foreach ($value->v6 as $subValue) {
                                $subValue->version = 6;
                                $subValue->cidr = $subValue->netmask;
                                $subValue->netmask = null;
                                $this->networks[] = new Network($subValue);
                            }
                        }
                    }
                    unset($parameters[$property]);

                    break;

                case 'kernel':
                    if (\is_object($value)) {
                        $this->kernel = new Kernel($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'size':
                    if (\is_object($value)) {
                        $this->size = new Size($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'region':
                    if (\is_object($value)) {
                        $this->region = new Region($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'image':
                    if (\is_object($value)) {
                        $this->image = new Image($value);
                    }
                    unset($parameters[$property]);

                    break;

                case 'next_backup_window':
                    $this->nextBackupWindow = new NextBackupWindow($value);
                    unset($parameters[$property]);

                    break;
            }
        }

        parent::build($parameters);

        $this->backupsEnabled = \in_array('backups', $this->features, true);
        $this->virtIOEnabled = \in_array('virtio', $this->features, true);
        $this->privateNetworkingEnabled = \in_array('private_networking', $this->features, true);
        $this->ipv6Enabled = \in_array('ipv6', $this->features, true);
    }

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = static::convertToIso8601($createdAt);
    }
}
