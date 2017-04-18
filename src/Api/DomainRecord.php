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

use DigitalOceanV2\Entity\DomainRecord as DomainRecordEntity;
use DigitalOceanV2\Exception\HttpException;
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
     * @return DomainRecordEntity[]
     */
    public function getAll($domainName)
    {
        $domainRecords = $this->adapter->get(sprintf('%s/domains/%s/records?per_page=%d', $this->endpoint, $domainName, 200));

        $domainRecords = json_decode($domainRecords);

        $this->extractMeta($domainRecords);

        return array_map(function ($domainRecord) {
            return new DomainRecordEntity($domainRecord);
        }, $domainRecords->domain_records);
    }

    /**
     * @param string $domainName
     * @param int    $id
     *
     * @return DomainRecordEntity
     */
    public function getById($domainName, $id)
    {
        $domainRecords = $this->adapter->get(sprintf('%s/domains/%s/records/%d', $this->endpoint, $domainName, $id));

        $domainRecords = json_decode($domainRecords);

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
     *
     * @throws HttpException|InvalidRecordException
     *
     * @return DomainRecordEntity
     */
    public function create($domainName, $type, $name, $data, $priority = null, $port = null, $weight = null)
    {
        switch ($type = strtoupper($type)) {
            case 'A':
            case 'AAAA':
            case 'CNAME':
            case 'TXT':
                $content = ['name' => $name, 'type' => $type, 'data' => $data];
                break;

            case 'NS':
                $content = ['type' => $type, 'data' => $data];
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

            default:
                throw new InvalidRecordException('The domain record type is invalid.');
        }

        $domainRecord = $this->adapter->post(sprintf('%s/domains/%s/records', $this->endpoint, $domainName), $content);

        $domainRecord = json_decode($domainRecord);

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
     *
     * @throws HttpException
     *
     * @return DomainRecordEntity
     */
    public function update($domainName, $recordId, $name = null, $data = null, $priority = null, $port = null, $weight = null)
    {
        $content = compact('name', 'data', 'priority', 'port', 'weight');
        $content = array_filter($content, function ($val) {
            return $val !== null;
        });

        return $this->updateFields($domainName, $recordId, $content);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param string $data
     *
     * @throws HttpException
     *
     * @return DomainRecordEntity
     */
    public function updateData($domainName, $recordId, $data)
    {
        return $this->updateFields($domainName, $recordId, ['data' => $data]);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param array  $fields
     *
     * @throws HttpException
     *
     * @return DomainRecordEntity
     */
    public function updateFields($domainName, $recordId, $fields)
    {
        $domainRecord = $this->adapter->put(sprintf('%s/domains/%s/records/%d', $this->endpoint, $domainName, $recordId), $fields);

        $domainRecord = json_decode($domainRecord);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     */
    public function delete($domainName, $recordId)
    {
        $this->adapter->delete(sprintf('%s/domains/%s/records/%d', $this->endpoint, $domainName, $recordId));
    }
}
