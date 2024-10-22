<?php

declare(strict_types=1);

namespace App\Tests\Models;

use App\Models\Plan;
use App\Models\Product;
use PHPUnit\Framework\TestCase;

class PlanTest extends TestCase
{
    private Plan $plan;

    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product('Test product');
        $this->plan = new Plan(
            $this->product,
            Plan::MONTHLY_BILLING,
            'USD',
            99.99
        );
    }

    public function testCreate(): void
    {   
        $this->assertSame($this->plan->getProduct(), $this->product);
        $this->assertEquals(Plan::MONTHLY_BILLING, $this->plan->getBillingType());
        $this->assertEquals('USD', $this->plan->getCurrency());
        $this->assertEquals(99.99, $this->plan->getPrice());
    }
}