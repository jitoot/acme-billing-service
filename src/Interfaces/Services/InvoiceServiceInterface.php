<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Models\Subscription;

interface InvoiceServiceInterface
{
    /**
     * Generate an invoice for a subscription
     *
     * @param Subscription $subscription
     * @return Subscription
     */
    public function invoiceSubscription(Subscription $subscription): Subscription;
}
