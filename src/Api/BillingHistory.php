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

use DigitalOceanV2\Entity\BillingHistory as BillingHistoryEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Simon Bennett <simon@snapshooter.io>
 */
class BillingHistory extends AbstractApi
{
    /**
     * @return BillingHistoryEntity[]
     *
     * @throws ExceptionInterface
     */
    public function listBillingHistory()
    {
        $billingHistory = $this->get('customers/my/billing_history');

        return \array_map(
            function ($item) {
                return new BillingHistoryEntity($item);
            },
            $billingHistory->billing_history
        );
    }
}
