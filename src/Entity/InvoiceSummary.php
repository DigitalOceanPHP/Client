<?php declare(strict_types=1);

namespace DigitalOceanV2\Entity;

use DigitalOceanV2\Entity\AbstractEntity;

/**
 * Class InvoiceSummary
 * @package DigitalOceanV2\Api
 */
final class InvoiceSummary extends AbstractEntity
{
    public $invoiceUuid;
    public $amount;
    public $invoicePeriod;
    public $updatedAt;

    /**
     * @param mixed $updatedAt
     * @return InvoiceSummary
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = static::convertToIso8601($updatedAt);
    }
}
