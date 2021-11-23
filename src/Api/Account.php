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

use DigitalOceanV2\Entity\Account as AccountEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Account extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return AccountEntity
     */
    public function getUserInformation()
    {
        $account = $this->get('account');

        return new AccountEntity($account->account);
    }
}
