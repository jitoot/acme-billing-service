<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\SubscriptionService;
use App\Services\InvoiceService;
use DateTime;
use Exception;

/**
 * Usage:
 *
 *     $ php bin/console acme:billing
 *
 * Manually register each Command class in bin/console
 *
 * symfony/console documentation: https://symfony.com/doc/current/components/console.html
 */
class BillingApplicationCommand extends Command
{
    protected static $defaultName = 'acme:billing';

    protected function configure()
    {
        $this->setName(self::$defaultName)
            ->addArgument('billing_date', InputArgument::OPTIONAL, 'Billing date to filter (leave empty for today)')
            ->setDescription('Runs the billing application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // create services
        $subscriptionService = new SubscriptionService();
        $invoiceService = new InvoiceService();
        
        if (!$input->getArgument('billing_date')) {
            $billingDate = new DateTime(); // run it for subscriptions where next billing date is older than today's billing date
        }
        else {
            try {
                $billingDate = new DateTime($input->getArgument('billing_date'));
            }
            catch (Exception $e) {
                $output->writeln('Invalid date string');
                return Command::FAILURE;
            }
        }

        $subscriptions = $subscriptionService->getActiveSubscriptionsByBillingDate($billingDate);

        foreach ($subscriptions as $subscription) {
            try {
                if (!$subscriptionService->isSubscriptionPlanBillingTypeValid($subscription->getPlan()->getBillingType())) {
                    throw new Exception("Plan is not valid");
                }
                $subscription = $invoiceService->invoiceSubscription($subscription);
            }
            catch (Exception $e) {
                // Error processing - log errors and notify team
            }
        }

        return Command::SUCCESS;
    }
}
