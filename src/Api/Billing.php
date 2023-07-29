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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Billing as BillingEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Lisa Ridley <lisa@codementality.com>
 */
class Billing extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return BillingEntity
     */
    public function getCustomerBalance()
    {
        $billings = $this->get('billing');

        return new BillingEntity($billing->billing);
    }
}
