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

use DigitalOceanV2\Entity\DomainRecord as DomainRecordEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\InvalidRecordException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class DomainRecord extends AbstractApi
{
    /**
     * @param string $domainName
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity[]
     */
    public function getAll(string $domainName)
    {
        $domainRecords = $this->get(\sprintf('domains/%s/records', $domainName));

        return \array_map(function ($domainRecord) {
            return new DomainRecordEntity($domainRecord);
        }, $domainRecords->domain_records);
    }

    /**
     * @param string $domainName
     * @param int    $id
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity
     */
    public function getById(string $domainName, int $id)
    {
        $domainRecords = $this->get(\sprintf('domains/%s/records/%d', $domainName, $id));

        return new DomainRecordEntity($domainRecords->domain_record);
    }

    /**
     * @param string $domainName
     * @param string $type
     * @param string $name
     * @param string $data
     * @param int    $priority
     * @param int    $port
     * @param int    $weight
     * @param int    $flags
     * @param string $tag
     * @param int    $ttl
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity
     */
    public function create(string $domainName, string $type, string $name, string $data, int $priority = null, int $port = null, int $weight = null, int $flags = null, string $tag = null, int $ttl = null)
    {
        switch ($type = \strtoupper($type)) {
            case 'A':
            case 'AAAA':
            case 'CNAME':
            case 'TXT':
            case 'NS':
                $content = ['name' => $name, 'type' => $type, 'data' => $data];

                break;

            case 'SRV':
                $content = [
                    'name' => $name,
                    'type' => $type,
                    'data' => $data,
                    'priority' => (int) $priority,
                    'port' => (int) $port,
                    'weight' => (int) $weight,
                ];

                break;

            case 'MX':
                $content = ['type' => $type, 'name' => $name, 'data' => $data, 'priority' => $priority];

                break;

            case 'CAA':
                $content = ['type' => $type, 'name' => $name, 'data' => $data, 'flags' => $flags, 'tag' => $tag];

                break;

            default:
                throw new InvalidRecordException('The domain record type is invalid.');
        }

        if (null !== $ttl) {
            $content['ttl'] = $ttl;
        }

        $domainRecord = $this->post(\sprintf('domains/%s/records', $domainName), $content);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string      $domainName
     * @param int         $recordId
     * @param string|null $name
     * @param string|null $data
     * @param int|null    $priority
     * @param int|null    $port
     * @param int|null    $weight
     * @param int|null    $flags
     * @param string|null $tag
     * @param int|null    $ttl
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity
     */
    public function update(string $domainName, int $recordId, ?string $name = null, ?string $data = null, ?int $priority = null, ?int $port = null, ?int $weight = null, ?int $flags = null, ?string $tag = null, ?int $ttl = null)
    {
        $content = [
            'name' => $name,
            'data' => $data,
            'priority' => $priority,
            'port' => $port,
            'weight' => $weight,
            'flags' => $flags,
            'tag' => $tag,
            'ttl' => $ttl,
        ];

        $content = \array_filter($content, function ($val) {
            return null !== $val;
        });

        return $this->updateFields($domainName, $recordId, $content);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param string $data
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity
     */
    public function updateData(string $domainName, int $recordId, string $data)
    {
        return $this->updateFields($domainName, $recordId, ['data' => $data]);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param array  $fields
     *
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity
     */
    public function updateFields(string $domainName, int $recordId, array $fields)
    {
        $domainRecord = $this->put(\sprintf('domains/%s/records/%d', $domainName, $recordId), $fields);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $domainName, int $recordId): void
    {
        $this->delete(\sprintf('domains/%s/records/%d', $domainName, $recordId));
    }
}
