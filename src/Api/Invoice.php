<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\BillingHistory as BillingHistoryEntity;
use DigitalOceanV2\Entity\InvoiceItem;
use DigitalOceanV2\Entity\InvoiceSummary;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Simon Bennett <simon@snapshooter.io>
 */
class Invoice extends AbstractApi
{
    /**
     * @return BillingHistoryEntity[]
     * @throws ExceptionInterface
     *
     */
    public function listAllInvoices()
    {
        $invoices = $this->get('customers/my/invoices');

        return \array_map(
            function ($item) {
                return new InvoiceSummary($item);
            },
            $invoices->invoices
        );
    }

    public function invoicePreview()
    {
        $invoices = $this->get('customers/my/invoices');

        return new InvoiceSummary($invoices->invoice_preview);
    }

    public function retrieveInvoiceByUUID(string $invoiceId)
    {
        $billingHistory = $this->get(\sprintf('customers/my/invoices/%s', $invoiceId));

        dd($billingHistory);

        return \array_map(
            function ($item) {
                return new InvoiceItem($item);
            },
            $billingHistory->invoice_items
        );
    }

    public function retrieveInvoiceCSVByUUID(string $invoiceId)
    {
        $billingHistory = $this->get(\sprintf('customers/my/invoices/%s/csv', $invoiceId));

        return $billingHistory;
    }
}
