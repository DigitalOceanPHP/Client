<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Key as KeyEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Key extends AbstractApi
{
    /**
     * @return KeyEntity[]
     */
    public function getAll()
    {
        $keys = $this->adapter->get(sprintf('%s/account/keys?per_page=%d', $this->endpoint, 200));

        $keys = json_decode($keys);

        $this->extractMeta($keys);

        return array_map(function ($key) {
            return new KeyEntity($key);
        }, $keys->ssh_keys);
    }

    /**
     * @param int $id
     *
     * @return KeyEntity
     */
    public function getById($id)
    {
        $key = $this->adapter->get(sprintf('%s/account/keys/%d', $this->endpoint, $id));

        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $fingerprint
     *
     * @return KeyEntity
     */
    public function getByFingerprint($fingerprint)
    {
        $key = $this->adapter->get(sprintf('%s/account/keys/%s', $this->endpoint, $fingerprint));

        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $name
     * @param string $publicKey
     *
     * @throws HttpException
     *
     * @return KeyEntity
     */
    public function create($name, $publicKey)
    {
        $key = $this->adapter->post(sprintf('%s/account/keys', $this->endpoint), ['name' => $name, 'public_key' => $publicKey]);

        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @throws HttpException
     *
     * @return KeyEntity
     */
    public function update($id, $name)
    {
        $key = $this->adapter->put(sprintf('%s/account/keys/%s', $this->endpoint, $id), ['name' => $name]);

        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     *
     * @throws HttpException
     */
    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/account/keys/%s', $this->endpoint, $id));
    }
}
