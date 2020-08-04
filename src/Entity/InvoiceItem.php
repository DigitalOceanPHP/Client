<?php declare(strict_types=1);

namespace DigitalOceanV2\Entity;

/**
 * Class InvoiceItem
 * @package DigitalOceanV2\Entity
 */
final class InvoiceItem extends AbstractEntity
{
    public $product;
    public $resourceUuid;
    public $groupDescription;
    public $description;
    public $duration;
    public $amount;
    public $durationUnit;

    public $startTime;
    public $endTime;
    public $projectName;
}
