<?php

declare(strict_types=1);

namespace App\Tests\Models;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product('Test product');
    }

    public function testCreate(): void
    {
        $this->assertEquals('Test product', $this->product->getName());
    }
}