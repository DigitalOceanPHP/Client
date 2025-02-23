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

use DigitalOceanV2\Entity\DomainRecord as DomainRecordEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\Exception\InvalidRecordException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class DomainRecord extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return DomainRecordEntity[]
     */
    public function getAll(string $domainName): array
    {
        $domainRecords = $this->get(\sprintf('domains/%s/records', $domainName));

        return \array_map(function ($domainRecord) {
            return new DomainRecordEntity($domainRecord);
        }, $domainRecords->domain_records);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getById(string $domainName, int $id): DomainRecordEntity
    {
        $domainRecords = $this->get(\sprintf('domains/%s/records/%d', $domainName, $id));

        return new DomainRecordEntity($domainRecords->domain_record);
    }

    /**
     * @throws ExceptionInterface
     */
    public function create(string $domainName, string $type, string $name, string $data, ?int $priority = null, ?int $port = null, ?int $weight = null, ?int $flags = null, ?string $tag = null, ?int $ttl = null): DomainRecordEntity
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
     * @throws ExceptionInterface
     */
    public function update(string $domainName, int $recordId, ?string $name = null, ?string $data = null, ?int $priority = null, ?int $port = null, ?int $weight = null, ?int $flags = null, ?string $tag = null, ?int $ttl = null): DomainRecordEntity
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
     * @throws ExceptionInterface
     */
    public function updateData(string $domainName, int $recordId, string $data): DomainRecordEntity
    {
        return $this->updateFields($domainName, $recordId, ['data' => $data]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateFields(string $domainName, int $recordId, array $fields): DomainRecordEntity
    {
        $domainRecord = $this->put(\sprintf('domains/%s/records/%d', $domainName, $recordId), $fields);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $domainName, int $recordId): void
    {
        $this->delete(\sprintf('domains/%s/records/%d', $domainName, $recordId));
    }
}
