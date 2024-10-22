<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Customer;
use App\Models\Plan;
use DateTime;

class Subscription
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';

    /**
     * Subscription ID
     *
     * @var string
     */
    private string $id;

    /**
     * Previous billing date
     *
     * @var DateTime
     */
    private DateTime $lastBillingDate;

    /**
     * Next billing date
     *
     * @var DateTime
     */
    private DateTime $nextBillingDate;

    /**
     * Subscription status
     *
     * @var string
     */
    private string $status;

    /**
     * Customer for the subscription
     *
     * @var Customer
     */
    private Customer $customer;

    /**
     * Plan for the subscription
     *
     * @var Plan
     */
    private Plan $plan;

    /**
     * Constructor
     *
     * @param Customer $customer
     * @param Plan $plan
     * @param DateTime $lastBillingDate
     * @param DateTime $nextBillingDate
     * @param string $status
     */
    public function __construct(
        Customer $customer,
        Plan $plan,
        string $id,
        DateTime $lastBillingDate,
        DateTime $nextBillingDate,
        string $status
    ) {
        $this->customer = $customer;
        $this->plan = $plan;
        $this->id = $id;
        $this->lastBillingDate = $lastBillingDate;
        $this->nextBillingDate = $nextBillingDate;
        $this->status = $status;
    }

    /**
     * Get Customer
     *
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * Get Plan
     *
     * @return Plan
     */
    public function getPlan(): Plan
    {
        return $this->plan;
    }

    /**
     * Get last billing date
     *
     * @return string
     */
    public function getLastBillingDate(): DateTime
    {
        return $this->lastBillingDate;
    }

    /**
     * Get next billing date
     *
     * @return string
     */
    public function getNextBillingDate(): DateTime
    {
        return $this->nextBillingDate;
    }

    /**
     * Get ID
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set last billing date
     *
     * @param DateTime $lastBillingDate
     * @return void
     */
    public function setLastBillingDate(DateTime $lastBillingDate): void
    {
        $this->lastBillingDate = $lastBillingDate;
    }

    /**
     * Set next billing date
     *
     * @param DateTime $nextBillingDate
     * @return void
     */
    public function setNextBillingDate(DateTime $nextBillingDate): void
    {
        $this->nextBillingDate = $nextBillingDate;
    }
}
