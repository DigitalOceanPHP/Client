<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Key as KeyEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Key extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return KeyEntity[]
     */
    public function getAll()
    {
        $keys = $this->get('account/keys');

        return \array_map(function ($key) {
            return new KeyEntity($key);
        }, $keys->ssh_keys);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function getById(int $id)
    {
        $key = $this->get(\sprintf('account/keys/%d', $id));

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $fingerprint
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function getByFingerprint(string $fingerprint)
    {
        $key = $this->get(\sprintf('account/keys/%s', $fingerprint));

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $name
     * @param string $publicKey
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function create(string $name, string $publicKey)
    {
        $key = $this->post('account/keys', [
            'name' => $name,
            'public_key' => $publicKey,
        ]);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return KeyEntity
     */
    public function update(string $id, string $name)
    {
        $key = $this->put(\sprintf('account/keys/%s', $id), [
            'name' => $name,
        ]);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('account/keys/%s', $id));
    }
}
