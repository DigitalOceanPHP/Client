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

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class Key extends AbstractApi
{
    /**
     * @return KeyEntity[]
     */
    public function getAll()
    {
        $keys = $this->adapter->get(sprintf('%s/account/keys?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
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
        $key = $this->adapter->get(sprintf('%s/account/keys/%d', self::ENDPOINT, $id));
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
        $key = $this->adapter->get(sprintf('%s/account/keys/%s', self::ENDPOINT, $fingerprint));
        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $name
     * @param string $publicKey
     *
     * @throws \RuntimeException
     *
     * @return KeyEntity
     */
    public function create($name, $publicKey)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode(array('name' => $name, 'public_key' => $publicKey));

        $key = $this->adapter->post(sprintf('%s/account/keys', self::ENDPOINT), $headers, $content);
        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return KeyEntity
     */
    public function update($id, $name)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode(array('name' => $name));

        $key = $this->adapter->put(sprintf('%s/account/keys/%d', self::ENDPOINT, $id), $headers, $content);
        $key = json_decode($key);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param int $id
     *
     * @throws \RuntimeException
     */
    public function delete($id)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/account/keys/%d', self::ENDPOINT, $id), $headers);
    }
}
