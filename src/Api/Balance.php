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

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Balance as BalanceEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Simon Bennett <simon@snapshooter.io>
 */
class Balance extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return BalanceEntity
     */
    public function getCustomerBalance()
    {
        $balance = $this->get('customers/my/balance');

        return new BalanceEntity($balance);
    }
}
