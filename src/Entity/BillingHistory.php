<?php

declare(strict_types=1);

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
 * @author Simon Bennett <simon@snapshooter.io>
 */
final class BillingHistory extends AbstractEntity
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $invoiceId;

    /**
     * @var string
     */
    public $invoiceUuid;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $type;
}
