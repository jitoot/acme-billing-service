<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Product;

class Plan
{
    const MONTHLY_BILLING = 'MONTHLY';
    const ANNUAL_BILLING = 'YEARLY';

    /**
     * Type of billing (MONTHLY or YEARLY)
     *
     * @var string
     */
    private string $billingType;

    /**
     * Currency of plan
     *
     * @var string
     */
    private string $currency;

    /**
     * Price of plan
     *
     * @var float
     */
    private float $price;

    /**
     * Product linked to plan
     *
     * @var Product
     */
    private Product $product;

    /**
     * Constructor
     *
     * @param Product $product
     * @param string $billingType
     * @param string $currency
     * @param float $price
     */
    public function __construct(Product $product, string $billingType, string $currency, float $price)
    {
        $this->product = $product;
        $this->billingType = $billingType;
        $this->currency = $currency;
        $this->price = $price;
    }

    /**
     * Get billing type
     *
     * @return string
     */
    public function getBillingType(): string
    {
        return $this->billingType;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}