# Acme Billing 

This is a simple subscription billing service for a code challenge.

## Schema

- Customer: Stores customer information.
- Product: Represents the items or services sold to customers.
- Plan: Defines a subscription plan for a product, including the price, currency, and the billing cycle (either monthly or annual).
- Subscription: A record linking a customer to a specific plan, indicating that the customer has subscribed to that plan.

Here is a simple schema diagram of the relationships between entities.

<img width="600" alt="schema" src="https://github.com/jitoot/acme-billing-service/blob/master/billing.png">

## Assumptions
1. Plans do not have additional fees/taxes.
2. Only the billing logic is implemented due to time constraints (no logic to add/remove subscription data). The subscription information is mocked via a JSON file located at `data` folder. Feel free to modify the JSON file to simulate different scenarios.

## Code structure
The core application is written as a console command using Symfony/Console package, and is located in src/Commands/BillingApplicationCommand.

The application uses two service classes (InvoiceService and SubscriptionService) that manages the retrieval of subscription data and executes the billing/invoicing action. Both classes implement their respective interface definitions and is coded to PSR-4 and OOP principles. All class definitions also have unit tests (except for the Command).

## Requirements
PHP 8.3

## Installation

1. Install [Composer](http://getcomposer.org/) package manager.
2. Clone or download repository as zip and unzip to a folder: https://github.com/jitoot/acme-billing-service/archive/refs/heads/master.zip
3. Install via composer: `composer install`

3. Run `php bin/console acme:billing` to run the billing service. 
Optionally you can pass in a date in YYYY-MM-DD format to filter the list of subscriptions based on the next billing date.

## Running test suite

1. Run the test suite:

        $ ./vendor/bin/phpunit
