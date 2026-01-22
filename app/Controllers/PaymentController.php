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

    /* Show payment page with Stripe Elements */
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

        // Subscription discount only applies to PRODUCTS, not to the subscription fee itself
        // The full subscription price is always charged
        $discountPercent = 0;
        $discountAmount = 0;
        $finalAmount = $plan['price'];
        $isFreeCheckout = false; // Subscriptions always require full payment

        require APP_ROOT . '/app/Views/subscription/payment.php';
    }

    /* Process Stripe payment and activate subscription*/
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


    public function processFreeSubscription()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /subscriptions');
            exit;
        }

        $planId = $_POST['plan_id'] ?? null;
        Session::set('error', 'Subscription requires payment. The subscription discount only applies to product purchases.');
        header('Location: /payment?plan_id=' . $planId);
        exit;
    }

 
    private function createFreeSubscriptionInvoice($userId, $plan, $discountAmount)
    {
        $invoiceModel = new Invoice();
        $itemModel = new InvoiceItem();

        $subtotal = $plan['price'];
        $taxRate = 0;
        $taxAmount = 0;
        $totalAmount = 0; 

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

        $invoiceModel->updatePaymentStatus($invoiceId, 0, 'Paid');

        $itemModel->addItem($invoiceId, [
            'name' => 'Subscription: ' . $plan['plan_name'] . ' (' . ucfirst($plan['billing_cycle']) . ')',
            'quantity' => 1,
            'price' => $plan['price'],
            'total' => $plan['price']
        ]);

        \App\Helpers\Mailer::sendInvoiceEmail($userId, $invoiceId);

        return $invoiceId;
    }

    private function createSubscriptionInvoice($userId, $plan, $transactionId)
    {
        $invoiceModel = new Invoice();
        $itemModel = new InvoiceItem();

        $subtotal = $plan['price'];
        $taxRate = 0; 
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

        $invoiceModel->updatePaymentStatus($invoiceId, $totalAmount, 'Paid');

        $itemModel->addItem($invoiceId, [
            'name' => 'Subscription: ' . $plan['plan_name'] . ' (' . ucfirst($plan['billing_cycle']) . ')',
            'quantity' => 1,
            'price' => $plan['price'],
            'total' => $plan['price']
        ]);

        \App\Helpers\Mailer::sendInvoiceEmail($userId, $invoiceId);

        return $invoiceId;
    }
}
