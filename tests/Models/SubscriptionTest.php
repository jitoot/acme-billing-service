<?php

declare(strict_types=1);

namespace App\Tests\Models;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use DateTime;
use PHPUnit\Event\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    private Subscription $subscription;

    private Customer $customer;

    private Plan $plan;

    private Product $product;

    protected function setUp(): void
    {
        $this->customer = new Customer('1', 'John', 'Doe', 'johndoe@example.com');
        $this->product = new Product('Test Product');
        $this->plan = new Plan($this->product, Plan::MONTHLY_BILLING, 'USD', 9.99);

        // Initialize a Subscription object
        $this->subscription = new Subscription(
            $this->customer,
            $this->plan,
            '1',
            new DateTime('2023-09-01'),
            new DateTime('2023-10-01'),
            Subscription::ACTIVE
        );
    }

    public function testCreate(): void
    {
        $this->assertSame($this->subscription->getCustomer(), $this->customer);
        $this->assertSame($this->subscription->getPlan(), $this->plan);
        $this->assertEquals('1', $this->subscription->getId());
        $this->assertEquals(new DateTime('2023-09-01'), $this->subscription->getLastBillingDate());
        $this->assertEquals(new DateTime('2023-10-01'), $this->subscription->getNextBillingDate());
        $this->assertEquals(Subscription::ACTIVE, $this->subscription->getStatus());
    }

    public function testSetLastBillingDate(): void
    {
        $newLastBillingDate = new DateTime('2023-09-15');
        $this->subscription->setLastBillingDate($newLastBillingDate);
        $this->assertEquals($newLastBillingDate, $this->subscription->getLastBillingDate());
    }

    public function testSetNextBillingDate(): void
    {
        $newNextBillingDate = new DateTime('2023-10-15');
        $this->subscription->setNextBillingDate($newNextBillingDate);
        $this->assertEquals($newNextBillingDate, $this->subscription->getNextBillingDate());
    }
}