<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Exception;
use DateTime;

class SubscriptionServiceTest extends TestCase
{
    private $testData;

    protected function setUp(): void
    {
        $this->testData = [
            [
                'id' => '1',
                'customer' => [
                    "id" => "100",
                    "firstName" => "Emily",
                    "lastName" => "Johnson",
                    "email" => "emily.johnson@x.dummyjson.com"
                ],
                "plan" => [
                    "product" => [
                        "name" => "Service 1"
                    ],
                    "billingType" => "MONTHLY",
                    "currency" => "USD",
                    "price" => 19.99
                ],
                "previousBillingDate" => "2024-09-21T23:28:00.000Z",
                "nextBillingDate" => "2024-10-21T23:28:00.000Z",
                "status" => "ACTIVE"
            ],
        ];
    }

    /**
     * Test retrieving active subscriptions with a future billing date
     *
     * @return void
     */
    public function testGetActiveSubscriptionsByBillingDateWithFutureBillingDate(): void
    {
        $mock = $this->setDataMock();

        $subscriptions = $mock->getActiveSubscriptionsByBillingDate(new DateTime('2025-01-01'));

        $this->assertEquals(count($subscriptions), 1);
    }

    /**
     * Test retrieving active subscriptions with a past billing date
     *
     * @return void
     */
    public function testGetActiveSubscriptionsByBillingDateWithPastBillingDate(): void
    {
        $mock = $this->setDataMock();

        $subscriptions = $mock->getActiveSubscriptionsByBillingDate(new DateTime('2023-01-01'));

        $this->assertEquals(count($subscriptions), 0);
    }

    /**
     * Test subscription plan billing type is valid
     *
     * @return void
     */
    public function testSubscriptionPlanBillingTypeIsValid(): void
    {
        $mock = $this->setDataMock();

        $subscriptions = $mock->getActiveSubscriptionsByBillingDate(new DateTime());

        $this->assertTrue($mock->isSubscriptionPlanBillingTypeValid($subscriptions[0]->getPlan()->getBillingType()));
    }

    /**
     * Test subscription plan billing type is not valid
     *
     * @return void
     */
    public function testSubscriptionPlanBillingTypeIsInvalid(): void
    {
        $mock = $this->getMockBuilder(SubscriptionService::class)
        ->onlyMethods(['loadData'])
        ->getMock();

        // return invalid type in plan > billingType
        $mock->expects($this->once())
            ->method('loadData')
            ->willReturn([
                [ 
                    'id' => '1',
                    'customer' => [
                        "id" => "100",
                        "firstName" => "Emily",
                        "lastName" => "Johnson",
                        "email" => "emily.johnson@x.dummyjson.com"
                    ],
                    "plan" => [
                        "product" => [
                            "name" => "Service 1"
                        ],
                        "billingType" => "INVALID",
                        "currency" => "USD",
                        "price" => 19.99
                    ],
                    "previousBillingDate" => "2024-09-21T23:28:00.000Z",
                    "nextBillingDate" => "2024-10-21T23:28:00.000Z",
                    "status" => "ACTIVE"
                ]
            ]);

        $subscriptions = $mock->getActiveSubscriptionsByBillingDate(new DateTime());

        $this->assertFalse($mock->isSubscriptionPlanBillingTypeValid($subscriptions[0]->getPlan()->getBillingType()));
    }

    /**
     * Test successful get of subscription data
     *
     * @return void
     */
    public function testGetAll(): void
    {
        $mock = $this->setDataMock();

        $subscriptions = $mock->getAll();

        $this->assertEquals(count($subscriptions), 1);
        $this->assertEquals('1', $subscriptions[0]->getId());
        $this->assertEquals('Emily', $subscriptions[0]->getCustomer()->getFirstName());
        $this->assertEquals('Johnson', $subscriptions[0]->getCustomer()->getLastName());
        $this->assertEquals('emily.johnson@x.dummyjson.com', $subscriptions[0]->getCustomer()->getEmail());
        $this->assertEquals('MONTHLY', $subscriptions[0]->getPlan()->getBillingType());
        $this->assertEquals('USD', $subscriptions[0]->getPlan()->getCurrency());
        $this->assertEquals(19.99, $subscriptions[0]->getPlan()->getPrice());
        $this->assertEquals(new DateTime('2024-09-21T23:28:00.000Z'), $subscriptions[0]->getLastBillingDate());
        $this->assertEquals(new DateTime('2024-10-21T23:28:00.000Z'), $subscriptions[0]->getNextBillingDate());
        $this->assertEquals(Subscription::ACTIVE, $subscriptions[0]->getStatus());
    }

    /**
     * Test failed get of subscription data
     *
     * @return void
     */
    public function testGetAllWithException(): void
    {
        $mock = $this->createMock(SubscriptionService::class);

        $mock->method('loadData')
            ->willThrowException(new Exception);

        $subscription = $mock->getAll();

        $this->assertEquals(count($subscription), count([]));
    }

    /**
     * Setup mock for loadData function
     *
     * @return void
     */
    protected function setDataMock(): MockObject
    {
        $mock = $this->getMockBuilder(SubscriptionService::class)
            ->onlyMethods(['loadData'])
            ->getMock();

        $mock->expects($this->once())
            ->method('loadData')
            ->willReturn($this->testData);

        return $mock;
    }
}