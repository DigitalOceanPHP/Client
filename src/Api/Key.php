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

use DigitalOceanV2\Entity\Key as KeyEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Antoine Kirk <contact@sbin.dk>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Key extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return KeyEntity[]
     */
    public function getAll(): array
    {
        $keys = $this->get('account/keys');

        return \array_map(function ($key) {
            return new KeyEntity($key);
        }, $keys->ssh_keys);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(int $id): KeyEntity
    {
        $key = $this->get(\sprintf('account/keys/%d', $id));

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getByFingerprint(string $fingerprint): KeyEntity
    {
        $key = $this->get(\sprintf('account/keys/%s', $fingerprint));

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @throws ExceptionInterface
     */
    public function create(string $name, string $publicKey): KeyEntity
    {
        $key = $this->post('account/keys', [
            'name' => $name,
            'public_key' => $publicKey,
        ]);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @throws ExceptionInterface
     */
    public function update(string $id, string $name): KeyEntity
    {
        $key = $this->put(\sprintf('account/keys/%s', $id), [
            'name' => $name,
        ]);

        return new KeyEntity($key->ssh_key);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('account/keys/%s', $id));
    }
}
