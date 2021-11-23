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

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Action extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return ActionEntity[]
     */
    public function getAll()
    {
        $actions = $this->get('actions');

        return \array_map(function ($action) {
            return new ActionEntity($action);
        }, $actions->actions);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return ActionEntity
     */
    public function getById(int $id)
    {
        $action = $this->get(\sprintf('actions/%d', $id));

        return new ActionEntity($action->action);
    }
}
