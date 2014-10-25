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
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 */
class Droplet extends AbstractEntity
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $memory;

    /**
     * @var integer
     */
    public $vcpus;

    /**
     * @var integer
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
     * @var String
     */
    public $sizeSlug;

    /**
     * @var boolean
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
     * @var integer[]
     */
    public $backupIds;

    /**
     * @var integer[]
     */
    public $snapshotIds;

    /**
     * @var integer[]
     */
    public $actionIds;

    /**
     * @var string[]
     */
    public $features;

    /**
     * @var boolean
     */
    public $backupsEnabled;

    /**
     * @var boolean
     */
    public $privateNetworkingEnabled;

    /**
     * @var boolean
     */
    public $ipv6Enabled;

    /**
     * @var boolean
     */
    public $virtIOEnabled;

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
                    $this->kernel = new Kernel($value);
                    break;

                case 'size':
                    $this->size = new Size($value);
                    break;

                case 'region':
                    $this->region = new Region($value);
                    break;

                case 'image':
                    $this->image = new Image($value);
                    break;

                default:
                    $this->{\DigitalOceanV2\convert_to_camel_case($property)} = $value;
            }
        }

        if (is_array($this->features) && count($this->features)) {
            $this->backupsEnabled = in_array("backups",$this->features);
            $this->virtIOEnabled = in_array("virtio",$this->features);
            $this->privateNetworkingEnabled = in_array("private_networking",$this->features);
            $this->ipv6Enabled = in_array("ipv6",$this->features);
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
