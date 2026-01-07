<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\StripeConfig;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Invoice;
use App\Models\InvoiceItem;

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
        $autoRenew = $_GET['auto_renew'] ?? 0;

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
        $autoRenew = isset($_POST['auto_renew']) ? 1 : 0;

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
                'amount' => (int)($plan['price'] * 100), // Convert to paise
                'currency' => 'inr',
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
                $end = date('Y-m-d', strtotime('+1 year'));
            } elseif ($billingCycle === 'weekly') {
                $end = date('Y-m-d', strtotime('+1 week'));
            } else {
                $end = date('Y-m-d', strtotime('+1 month'));
            }

            // Create subscription
            $this->subscriptionModel->subscribe([
                'user_id' => $userId,
                'plan_id' => $planId,
                'start_date' => $start,
                'end_date' => $end,
                'auto_renew' => $autoRenew
            ]);

            // Create subscription invoice
            $this->createSubscriptionInvoice($userId, $plan, $charge->id);

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
     * Create invoice for subscription payment
     */
    private function createSubscriptionInvoice($userId, $plan, $transactionId)
    {
        $invoiceModel = new Invoice();
        $itemModel = new InvoiceItem();

        $subtotal = $plan['price'];
        $taxRate = 18; // GST 18%
        $taxAmount = $subtotal * ($taxRate / 100);
        $totalAmount = $subtotal + $taxAmount;

        $invoiceId = $invoiceModel->create([
            'created_by' => $userId,
            'client_id' => $userId,
            'invoice_number' => 'SUB-' . date('Ymd-His'),
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'subtotal' => $subtotal,
            'tax_type' => 'GST',
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'total_amount' => $totalAmount,
            'status' => 'paid',
            'notes' => 'Subscription Payment - ' . $plan['plan_name'] . ' | Transaction ID: ' . $transactionId
        ]);

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
