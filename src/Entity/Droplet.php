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
     * @var boolean
     */
    public $locked;

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
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            switch ($property) {
                case 'networks':
                    if (!empty($value->v4)) {
                        $this->networks[] = new Network($value->v4[0]);
                    }

                    if (!empty($value->v6)) {
                        $this->networks[] = new Network($value->v6[0]);
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
    }
}
