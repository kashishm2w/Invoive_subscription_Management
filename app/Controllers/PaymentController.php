<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\StripeConfig;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;

class PaymentController
{
    private Subscription $subscriptionModel;
    private SubscriptionPlan $planModel;

    public function __construct()
    {
        Session::start();
        $this->subscriptionModel = new Subscription();
        $this->planModel = new SubscriptionPlan();
    }

    /**
     * Show payment page with Stripe Elements
     */
    public function showPaymentPage()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $planId = $_GET['plan_id'] ?? null;

        if (!$planId) {
            Session::set('error', 'Plan not selected');
            header('Location: /subscriptions');
            exit;
        }

        $plan = $this->planModel->getPlan($planId);

        if (!$plan) {
            Session::set('error', 'Plan not found');
            header('Location: /subscriptions');
            exit;
        }

        // Check if already has active subscription
        $activeSubscription = $this->subscriptionModel->getActiveSubscription(Session::get('user_id'));
        if ($activeSubscription) {
            Session::set('error', 'You already have an active plan: ' . $activeSubscription['plan_name']);
            header('Location: /subscriptions');
            exit;
        }

        $stripePublishableKey = StripeConfig::getPublishableKey();

        // Calculate discount
        $discountPercent = (float)($plan['discount_percent'] ?? 0);
        $discountAmount = $plan['price'] * ($discountPercent / 100);
        $finalAmount = $plan['price'] - $discountAmount;
        $isFreeCheckout = $finalAmount <= 0;

        require APP_ROOT . '/app/Views/subscription/payment.php';
    }

    /**
     * Process Stripe payment and activate subscription
     */
    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /subscriptions');
            exit;
        }

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $token = $_POST['stripeToken'] ?? null;
        $planId = $_POST['plan_id'] ?? null;

        if (!$token || !$planId) {
            Session::set('error', 'Payment failed. Please try again.');
            header('Location: /subscriptions');
            exit;
        }

        $plan = $this->planModel->getPlan($planId);

        if (!$plan) {
            Session::set('error', 'Plan not found');
            header('Location: /subscriptions');
            exit;
        }

        // Initialize Stripe
        StripeConfig::init();

        try {
            // Create Stripe charge
            $charge = \Stripe\Charge::create([
                'amount' => (int) round($plan['price'] * 100),
                'currency' => 'usd',
                'description' => 'Subscription: ' . $plan['plan_name'],
                'source' => $token,
                'metadata' => [
                    'user_id' => Session::get('user_id'),
                    'plan_id' => $planId,
                    'plan_name' => $plan['plan_name']
                ]
            ]);

            // Payment successful - activate subscription
            $userId = Session::get('user_id');
            $start = date('Y-m-d');

            // Calculate end date based on billing cycle
            $billingCycle = strtolower($plan['billing_cycle']);
            if ($billingCycle === 'yearly') {
                $end = date('Y-m-d', strtotime('+1 year '));
            } elseif ($billingCycle === 'weekly') {
                $end = date('Y-m-d', strtotime('+1 week'));
            } else {
                $end = date('Y-m-d', strtotime('+1 month'));
            }

            // Create subscription
            $subscriptionResult = $this->subscriptionModel->subscribe([
                'user_id' => $userId,
                'plan_id' => $planId,
                'start_date' => $start,
                'end_date' => $end,
            ]);

            if (!$subscriptionResult) {
                Session::set('error', 'Payment successful but failed to create subscription. Please contact support.');
                header('Location: /subscriptions');
                exit;
            }

            // Create subscription invoice
            $invoiceId = $this->createSubscriptionInvoice($userId, $plan, $charge->id);

            if (!$invoiceId) {
                Session::set('error', 'Payment successful but failed to create invoice. Please contact support.');
                header('Location: /subscriptions');
                exit;
            }

            // Record payment in payments table
            $paymentModel = new Payment();
            $paymentModel->create([
                'invoice_id' => $invoiceId,
                'user_id' => $userId,
                'amount' => $plan['price'],
                'payment_method' => 'stripe',
                'transaction_id' => $charge->id,
                'status' => 'completed',
                'notes' => 'Subscription Payment - ' . $plan['plan_name']
            ]);

            Session::set('success', 'Payment successful! Your subscription is now active.');
            header('Location: /subscriptions');
            exit;
        } catch (\Stripe\Exception\CardException $e) {
            Session::set('error', 'Card declined: ' . $e->getMessage());
            header('Location: /subscriptions');
            exit;
        } catch (\Exception $e) {
            Session::set('error', 'Payment failed: ' . $e->getMessage());
            header('Location: /subscriptions');
            exit;
        }
    }

    /**
     * Process free subscription (100% discount)
     */
    public function processFreeSubscription()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /subscriptions');
            exit;
        }

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $planId = $_POST['plan_id'] ?? null;

        if (!$planId) {
            Session::set('error', 'Plan not selected. Please try again.');
            header('Location: /subscriptions');
            exit;
        }

        $plan = $this->planModel->getPlan($planId);

        if (!$plan) {
            Session::set('error', 'Plan not found');
            header('Location: /subscriptions');
            exit;
        }

        // Verify this is actually a 100% discount plan
        $discountPercent = (float)($plan['discount_percent'] ?? 0);
        $discountAmount = $plan['price'] * ($discountPercent / 100);
        $finalAmount = $plan['price'] - $discountAmount;

        if ($finalAmount > 0) {
            Session::set('error', 'This plan requires payment. Please use the payment form.');
            header('Location: /payment?plan_id=' . $planId);
            exit;
        }

        // Check if already has active subscription
        $activeSubscription = $this->subscriptionModel->getActiveSubscription(Session::get('user_id'));
        if ($activeSubscription) {
            Session::set('error', 'You already have an active plan: ' . $activeSubscription['plan_name']);
            header('Location: /subscriptions');
            exit;
        }

        try {
            $userId = Session::get('user_id');
            $start = date('Y-m-d');

            // Calculate end date based on billing cycle
            $billingCycle = strtolower($plan['billing_cycle']);
            if ($billingCycle === 'yearly') {
                $end = date('Y-m-d', strtotime('+1 year'));
            } elseif ($billingCycle === 'weekly') {
                $end = date('Y-m-d', strtotime('+1 week'));
            } else {
                $end = date('Y-m-d', strtotime('+1 month'));
            }

            // Create subscription
            $subscriptionResult = $this->subscriptionModel->subscribe([
                'user_id' => $userId,
                'plan_id' => $planId,
                'start_date' => $start,
                'end_date' => $end,
            ]);

            if (!$subscriptionResult) {
                Session::set('error', 'Failed to create subscription. Please contact support.');
                header('Location: /subscriptions');
                exit;
            }

            // Create subscription invoice with discount
            $invoiceId = $this->createFreeSubscriptionInvoice($userId, $plan, $discountAmount);

            if (!$invoiceId) {
                Session::set('error', 'Failed to create invoice. Please contact support.');
                header('Location: /subscriptions');
                exit;
            }

            Session::set('success', 'Subscription activated successfully! Your subscription is now active.');
            header('Location: /subscriptions');
            exit;
        } catch (\Exception $e) {
            Session::set('error', 'An error occurred: ' . $e->getMessage());
            header('Location: /subscriptions');
            exit;
        }
    }

    /**
     * Create invoice for free subscription (100% discount)
     */
    private function createFreeSubscriptionInvoice($userId, $plan, $discountAmount)
    {
        $invoiceModel = new Invoice();
        $itemModel = new InvoiceItem();

        $subtotal = $plan['price'];
        $taxRate = 0;
        $taxAmount = 0;
        $totalAmount = 0; // Free subscription

        $invoiceId = $invoiceModel->create([
            'created_by' => $userId,
            'client_id' => $userId,
            'invoice_number' => 'SUB-' . date('Ymd-His'),
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'subtotal' => $subtotal,
            'tax_type' => 'NONE',
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => $discountAmount,
            'total_amount' => $totalAmount,
            'status' => 'Paid',
            'notes' => 'Free Subscription (' . (int)$plan['discount_percent'] . '% discount) - ' . $plan['plan_name']
        ]);

        // Set amount_paid = 0 (nothing paid) and due_amount = 0 (nothing owed)
        $invoiceModel->updatePaymentStatus($invoiceId, 0, 'Paid');

        // Add invoice item
        $itemModel->addItem($invoiceId, [
            'name' => 'Subscription: ' . $plan['plan_name'] . ' (' . ucfirst($plan['billing_cycle']) . ')',
            'quantity' => 1,
            'price' => $plan['price'],
            'total' => $plan['price']
        ]);

        // Send invoice email to user
        \App\Helpers\Mailer::sendInvoiceEmail($userId, $invoiceId);

        return $invoiceId;
    }

    /*   Create invoice for subscription payment */
    private function createSubscriptionInvoice($userId, $plan, $transactionId)
    {
        $invoiceModel = new Invoice();
        $itemModel = new InvoiceItem();

        $subtotal = $plan['price'];
        $taxRate = 0; // No tax on subscriptions
        $taxAmount = 0;
        $totalAmount = $subtotal;

        $invoiceId = $invoiceModel->create([
            'created_by' => $userId,
            'client_id' => $userId,
            'invoice_number' => 'SUB-' . date('Ymd-His'),
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'subtotal' => $subtotal,
            'tax_type' => 'NONE',
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'total_amount' => $totalAmount,
            'status' => 'Paid',
            'notes' => 'Subscription Payment - ' . $plan['plan_name'] . ' | Transaction ID: ' . $transactionId
        ]);

        // Set amount_paid and due_amount for fully paid subscription
        $invoiceModel->updatePaymentStatus($invoiceId, $totalAmount, 'Paid');

        // Add invoice item
        $itemModel->addItem($invoiceId, [
            'name' => 'Subscription: ' . $plan['plan_name'] . ' (' . ucfirst($plan['billing_cycle']) . ')',
            'quantity' => 1,
            'price' => $plan['price'],
            'total' => $plan['price']
        ]);

        // Send invoice email to user
        \App\Helpers\Mailer::sendInvoiceEmail($userId, $invoiceId);

        return $invoiceId;
    }
}
