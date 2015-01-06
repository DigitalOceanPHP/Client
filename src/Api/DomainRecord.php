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

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
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
        $domainRecords = $this->adapter->get(sprintf('%s/domains/%s/records?per_page=%d', self::ENDPOINT, $domainName, PHP_INT_MAX));
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
        $domainRecords = $this->adapter->get(sprintf('%s/domains/%s/records/%d', self::ENDPOINT, $domainName, $id));
        $domainRecords = json_decode($domainRecords);

        return new DomainRecordEntity($domainRecords->domain_record);
    }

    /**
     * @param string $domainName
     * @param string $type
     * @param string $name
     * @param string $data
     * @param int    $priority   (optional)
     * @param int    $port       (optional)
     * @param int    $weight     (optional)
     *
     * @throws \RuntimeException
     *
     * @return DomainRecordEntity
     */
    public function create($domainName, $type, $name, $data, $priority = null, $port = null, $weight = null)
    {
        $headers = array('Content-Type: application/json');
        $content = '';

        switch ($type = strtoupper($type)) {
            case 'A':
            case 'AAAA':
            case 'CNAME':
            case 'TXT':
                $content .= json_encode(array('name' => $name, 'type' => $type, 'data' => $data));
                break;

            case 'NS':
                $content .= json_encode(array('type' => $type, 'data' => $data));
                break;

            case 'SRV':
                $content .= json_encode(array(
                    'name' => $name,
                    'type' => $type,
                    'data' => $data,
                    'priority' => (int) $priority,
                    'port' => (int) $port,
                    'weight' => (int) $weight
                ));
                break;

            case 'MX':
                $content .= json_encode(array('type' => $type, 'data' => $data, 'priority' => $priority));
                break;

            default:
                throw new \RuntimeException('Domain record type is invalid.');
        }

        $domainRecord = $this->adapter->post(sprintf('%s/domains/%s/records', self::ENDPOINT, $domainName), $headers, $content);
        $domainRecord = json_decode($domainRecord);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return DomainRecordEntity
     */
    public function update($domainName, $recordId, $name)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode(array('name' => $name));

        $domainRecord = $this->adapter->put(sprintf('%s/domains/%s/records/%d', self::ENDPOINT, $domainName, $recordId), $headers, $content);
        $domainRecord = json_decode($domainRecord);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     * @param string $data
     *
     * @throws \RuntimeException
     *
     * @return DomainRecordEntity
     */
    public function updateData($domainName, $recordId, $data)
    {
        $headers = array('Content-Type: application/json');
        $content = json_encode(array('data' => $data));

        $domainRecord = $this->adapter->put(sprintf('%s/domains/%s/records/%d', self::ENDPOINT, $domainName, $recordId), $headers, $content);
        $domainRecord = json_decode($domainRecord);

        return new DomainRecordEntity($domainRecord->domain_record);
    }

    /**
     * @param string $domainName
     * @param int    $recordId
     */
    public function delete($domainName, $recordId)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/domains/%s/records/%d', self::ENDPOINT, $domainName, $recordId), $headers);
    }
}
