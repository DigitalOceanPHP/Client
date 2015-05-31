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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Droplet extends AbstractEntity
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
     * @var String
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
     * @var Network[]
     */
    public $networks;

    /**
     * @var int[]
     */
    public $backupIds;

    /**
     * @var int[]
     */
    public $snapshotIds;

    /**
     * @var string[]
     */
    public $features;

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
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'networks':
                    if (is_object($value)) {
                        if (property_exists($value, 'v4')) {
                            foreach ($value->v4 as $subProperty => $subValue) {
                                $this->networks[] = new Network($subValue);
                            }
                        }

                        if (property_exists($value, 'v6')) {
                            foreach ($value->v6 as $subProperty => $subValue) {
                                $this->networks[] = new Network($subValue);
                            }
                        }
                    }
                    break;

                case 'kernel':
                    if (is_object($value)) {
                        $this->kernel = new Kernel($value);
                    }
                    break;

                case 'size':
                    if (is_object($value)) {
                        $this->size = new Size($value);
                    }
                    break;

                case 'region':
                    if (is_object($value)) {
                        $this->region = new Region($value);
                    }
                    break;

                case 'image':
                    if (is_object($value)) {
                        $this->image = new Image($value);
                    }
                    break;

                case 'next_backup_window':
                    $this->nextBackupWindow = new NextBackupWindow($value);
                    break;

                default:
                    $this->{\DigitalOceanV2\convert_to_camel_case($property)} = $value;
            }
        }

        if (is_array($this->features) && count($this->features)) {
            $this->backupsEnabled = in_array("backups", $this->features);
            $this->virtIOEnabled = in_array("virtio", $this->features);
            $this->privateNetworkingEnabled = in_array("private_networking", $this->features);
            $this->ipv6Enabled = in_array("ipv6", $this->features);
        }
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->convertDateTime($createdAt);
    }
}
