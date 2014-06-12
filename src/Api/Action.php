<?php

/**
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Action as ActionEntity;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Action extends AbstractApi
{
    /**
     * @return Action[]
     */
    public function getAll()
    {
        $actions = $this->adapter->get(sprintf("%s/actions", self::ENDPOINT));
        $actions = json_decode($actions);

        $results = array();
        foreach ($actions->actions as $action) {
            $result[] = new ActionEntity($action);
        }

        return $result;
    }

    /**
     * @param  integer $id
     * @return Action
     */
    public function getById($id)
    {
        $action = $this->adapter->get(sprintf("%s/actions/%d", self::ENDPOINT, $id));
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
