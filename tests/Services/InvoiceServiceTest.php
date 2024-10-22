<?php

declare(strict_types=1);

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use App\Services\InvoiceService;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Product;
use DateTime;
use DateInterval;

class InvoiceServiceTest extends TestCase
{
    private InvoiceService $invoiceService;
    private Customer $customer;
    private Product $product;
    private Plan $monthlyPlan;
    private Plan $annualPlan;
    private Subscription $monthlySubscription;
    private Subscription $annualSubscription;

    protected function setUp(): void
    {
        // Set up InvoiceService
        $this->invoiceService = new InvoiceService();
        $this->invoiceService->disableOutput();

        // Set up Customer object
        $this->customer = new Customer('1', 'John', 'Doe', 'john.doe@example.com');

        // Set up Product object
        $this->product = new Product('Test Product');

        // Set up Plans (monthly and annual)
        $this->monthlyPlan = new Plan($this->product, Plan::MONTHLY_BILLING, 'USD', 9.99);
        $this->annualPlan = new Plan($this->product, Plan::ANNUAL_BILLING, 'USD', 99.99);

        // Set up Subscriptions (monthly and annual)
        $this->monthlySubscription = new Subscription(
            $this->customer,
            $this->monthlyPlan,
            '1',
            new DateTime('2024-09-01'),
            new DateTime('2024-10-01'),
            Subscription::ACTIVE
        );

        $this->annualSubscription = new Subscription(
            $this->customer,
            $this->annualPlan,
            '2',
            new DateTime('2023-10-01'),
            new DateTime('2024-10-01'),
            Subscription::ACTIVE
        );
    }

    public function testInvoiceSubscriptionWithMonthlyBilling(): void
    {
        $updatedSubscription = $this->invoiceService->invoiceSubscription($this->monthlySubscription);

        $this->assertEquals((new DateTime())->format("Y-m-d H:i:s"), $updatedSubscription->getLastBillingDate()->format("Y-m-d H:i:s"));
        $this->assertEquals(new DateTime('2024-11-01'), $updatedSubscription->getNextBillingDate());
    }

    public function testInvoiceSubscriptionWithAnnualBilling(): void
    {
        $updatedSubscription = $this->invoiceService->invoiceSubscription($this->annualSubscription);

        $this->assertEquals((new DateTime())->format("Y-m-d H:i:s"), $updatedSubscription->getLastBillingDate()->format("Y-m-d H:i:s"));
        $this->assertEquals(new DateTime('2025-10-01'), $updatedSubscription->getNextBillingDate());
    }

    public function testUpdateLastBillingDate(): void
    {
        $date = new DateTime('2023-09-30');

        $updatedSubscription = $this->invoiceService->updateLastBillingDate($this->monthlySubscription, $date);

        $this->assertEquals($date, $updatedSubscription->getLastBillingDate());
    }

    public function testUpdateNextMonthlyBillingDate(): void
    {
        $updatedSubscription = $this->invoiceService->updateNextMonthlyBillingDate($this->monthlySubscription);

        $this->assertEquals(new DateTime('2024-11-01'), $updatedSubscription->getNextBillingDate());
    }

    public function testUpdateNextAnnualBillingDate(): void
    {
        $updatedSubscription = $this->invoiceService->updateNextAnnualBillingDate($this->annualSubscription);

        $this->assertEquals(new DateTime('2025-10-01'), $updatedSubscription->getNextBillingDate());
    }
}