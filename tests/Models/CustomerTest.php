<?php

declare(strict_types=1);

namespace App\Tests\Models;

use App\Models\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        $this->customer = new Customer('1', 'John', 'Doe', 'johndoe@example.com');
    }

    public function testCreate(): void
    {   
        $this->assertEquals('1', $this->customer->getId());
        $this->assertEquals('John', $this->customer->getFirstName());
        $this->assertEquals('Doe', $this->customer->getLastName());
        $this->assertEquals('John Doe', $this->customer->getFullName());
        $this->assertEquals('johndoe@example.com', $this->customer->getEmail());
    }
}