<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Lisa Ridley <lisa@codementality.com>
 */

namespace DigitalOceanV2\Entity;

/**
 * @author Lisa Ridley <lisa@codementality.com>
 */
final class Billing extends AbstractEntity
{
    /**
     * @var int
     */
    public $monthToDateBalance;

    /**
     * @var int
     */
    public $accountBalance;

    /**
     * @var int
     */
    public $monthToDateUsage;

    /**
     * @var string
     */
    public $generatedDate;

    /**
     * @param array $parameters
     *
     * @return void
     */
    public function build(array $parameters): void
    {
        parent::build($parameters);
    }
}
