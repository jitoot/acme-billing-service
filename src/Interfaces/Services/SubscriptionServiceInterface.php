<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use DateTime;

interface SubscriptionServiceInterface
{
    /**
     * Get active subscriptions
     *
     * @return array
     */
    public function getActiveSubscriptionsByBillingDate(DateTime $billingDate): array;

    /**
     * Is subscription plan valid
     *
     * @param string $plan
     * @return boolean
     */
    public function isSubscriptionPlanBillingTypeValid(string $plan): bool;
}
