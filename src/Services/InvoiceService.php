<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Services\InvoiceServiceInterface;
use App\Models\Subscription;
use App\Models\Plan;
use DateTime;
use DateInterval;

class InvoiceService implements InvoiceServiceInterface
{
    private $disableOutput = false;

    public function invoiceSubscription(Subscription $subscription): Subscription
    {
        $customer = $subscription->getCustomer();
        $plan = $subscription->getPlan();
        $product = $plan->getProduct();

        /*
        
        Simulate invoicing the customer. In a real world scenario, an invoice record can be created with the 
        subscription details, with payment gateway page and email sent to customer

        $invoiceData = [
            'customer_id' => $customer->getId(),
            'subscription_id' => $subscription->getId(),
            'currency' => $plan->getCurrency(),
            'amount' => $plan->getPrice(),
            'date' => new DateTime('Y-m-d H:i:s'),
        ];

        */

        $billingType = $plan->getBillingType();
        
        $this->log("[subscription ID {$subscription->getId()}]: Subscription is a {$plan->getCurrency()} {$plan->getPrice()} {$billingType} plan for 'Product {$product->getName()}'");
        $this->log("[subscription ID {$subscription->getId()}]: Last billing date is {$subscription->getLastBillingDate()->format('Y-m-d')}");

        $now = new DateTime();

        // update billing dates
        switch ($billingType) {
            case Plan::MONTHLY_BILLING:
                $subscription = $this->updateNextMonthlyBillingDate($subscription, $now);
                break;
            case Plan::ANNUAL_BILLING:
                $subscription = $this->updateNextAnnualBillingDate($subscription, $now);
                break;
        }
        
        $subscription = $this->updateLastBillingDate($subscription, $now);
        
        $this->log("[subscription ID {$subscription->getId()}]: Generated invoice for billing date {$now->format('Y-m-d')}, next billing date is {$subscription->getNextBillingDate()->format('Y-m-d')}");
        $this->log("[subscription ID {$subscription->getId()}]: Sent email to customer at {$customer->getEmail()}");
        
        return $subscription;
    }

    public function updateLastBillingDate(Subscription $subscription, DateTime $date): Subscription
    {
        $newDate = clone $date;
        $subscription->setLastBillingDate($newDate);

        return $subscription;
    }

    public function updateNextMonthlyBillingDate(Subscription $subscription): Subscription
    {
        $newDate = clone $subscription->getNextBillingDate();
        $subscription->setNextBillingDate($newDate->add(new DateInterval('P1M')));

        return $subscription;
    }

    public function updateNextAnnualBillingDate(Subscription $subscription): Subscription
    {
        $newDate = clone $subscription->getNextBillingDate();
        $subscription->setNextBillingDate($newDate->add(new DateInterval('P1Y')));

        return $subscription;
    }

    public function disableOutput(): void
    {
        $this->disableOutput = true;
    }

    protected function log(string $message): void
    {
        if (!$this->disableOutput)
            echo $message.PHP_EOL;
    }
}
