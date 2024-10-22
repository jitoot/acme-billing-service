<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Services\SubscriptionServiceInterface;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use Exception;
use DateTime;

class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * Get active subscriptions based on billing date
     *
     * @return array
     */
    public function getActiveSubscriptionsByBillingDate(DateTime $billingDate): array
    {
        return array_filter($this->getAll(), function(Subscription $subscription) use ($billingDate) {
            return $subscription->getStatus() == Subscription::ACTIVE && $subscription->getNextBillingDate() < $billingDate;
        });
    }

    /**
     * Is subscription plan valid
     *
     * @return bool
     */
    public function isSubscriptionPlanBillingTypeValid(string $plan): bool
    {
        return in_array($plan, [
            Plan::ANNUAL_BILLING,
            Plan::MONTHLY_BILLING,
        ]);
    }

    /**
     * Get all subscriptions. This is a simplified example to retrieve mock data
     *
     * @return array
     */
    public function getAll(): array
    {
        $subscriptions = [];

        // for this exercise, the data is loaded from a JSON file instead of a database or API endpoint 
        try {
            $subscriptionsData = $this->loadData();

            foreach ($subscriptionsData as $subscriptionData) {
                $customerData = $subscriptionData['customer'];
                $planData = $subscriptionData['plan'];
                $productData = $planData['product'];

                // Create Product object
                $product = new Product($productData['name']);

                // Create Plan object
                $plan = new Plan(
                    $product,
                    $planData['billingType'],
                    $planData['currency'],
                    $planData['price'],
                );
                
                // Create Customer object
                $customer = new Customer(
                    $customerData['id'],
                    $customerData['firstName'],
                    $customerData['lastName'],
                    $customerData['email']
                );

                // Create Subscription object
                $subscription = new Subscription(
                    $customer,
                    $plan,
                    $subscriptionData['id'],
                    new DateTime($subscriptionData['previousBillingDate']),
                    new DateTime($subscriptionData['nextBillingDate']),
                    $subscriptionData['status']
                );

                $subscriptions[] = $subscription;
            }

        } catch (Exception $e) {
            // Log error and notify team
        }

        return $subscriptions;
    }

    /**
     * Load mock data
     *
     * @return array
     */
    public function loadData(): array
    {
        $jsonData = file_get_contents(__DIR__.'/../../data/subscriptions.json');
        $subscriptionsData = json_decode($jsonData, true);

        return $subscriptionsData;
    }
}