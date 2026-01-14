<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Product;

class CartController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
        Session::start();
    }

    // Show Cart Page
    public function showCart()
    {
        $cart = Session::get('cart') ?? [];
        $totalAmount = 0;

        // Add current stock info to each cart item
        foreach ($cart as $productId => &$item) {
            $product = $this->productModel->getById($productId);
            $item['available_stock'] = $product ? $product['quantity'] : 0;
            $totalAmount += $item['price'] * $item['quantity'] + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];
        }
        unset($item); // Break reference

        // Check for subscription discount
        $subscription = null;
        $discountPercent = 0;
        $discountAmount = 0;
        $finalTotal = $totalAmount;

        if (Session::has('user_id')) {
            $subscriptionModel = new \App\Models\Subscription();
            $subscription = $subscriptionModel->getActiveSubscription(Session::get('user_id'));
            
            if ($subscription && isset($subscription['discount_percent']) && $subscription['discount_percent'] > 0) {
                $discountPercent = (float)$subscription['discount_percent'];
                $discountAmount = $totalAmount * ($discountPercent / 100);
                $finalTotal = $totalAmount - $discountAmount;
            }
        }

        require APP_ROOT . '/app/Views/cart/show.php';
    }

    // Add Item to Cart (AJAX POST)
    public function addItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity  = (int)($_POST['quantity'] ?? 1);

            if (!$productId) {
                echo json_encode(['success'=>false, 'error'=>'Product ID is required']);
                exit;
            }

            $product = $this->productModel->getById($productId);

            if (!$product) {
                echo json_encode(['success'=>false, 'error'=>'Product not found']);
                exit;
            }

            if ($quantity > $product['quantity']) {
                echo json_encode(['success'=>false, 'error'=>'Quantity exceeds available stock']);
                exit;
            }

            // Get current cart from session
            $cart = Session::get('cart') ?? [];

            // If product already in cart, increase quantity
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;

                // Prevent exceeding stock
                if ($cart[$productId]['quantity'] > $product['quantity']) {
                    $cart[$productId]['quantity'] = $product['quantity'];
                }
            } else {
                $cart[$productId] = [
                    'id'=> $product['id'],
                    'name'=> $product['name'],
                    'price'=> $product['price'],
                    'tax_percent'=> $product['tax_percent'],
                    'quantity'=> $quantity,
                    'poster'=> $product['poster'] ?? 'default.png'
                ];
            }

            Session::set('cart', $cart);

            echo json_encode(['success'=>true]);
            exit;
        }
    }
    public function listProducts()
{
    $products = $this->productModel->getAll();

    // Get cart from session
    $cart = Session::get('cart') ?? [];
    $cartProductIds = array_keys($cart); // just product IDs for easy lookup

    require APP_ROOT . '/app/Views/products/list.php';
}
// Update quantity
public function updateItem()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['product_id'] ?? null;
        $quantity  = (int)($_POST['quantity'] ?? 1);

        // Fetch product to check stock
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            echo json_encode(['success' => false, 'error' => 'Product not found']);
            exit;
        }
        
        // Check if requested quantity exceeds stock
        if ($quantity > $product['quantity']) {
            echo json_encode([
                'success' => false,
                'error' => 'Only ' . $product['quantity'] . ' items available',
                'available_stock' => $product['quantity']
            ]);
            exit;
        }

        $cart = Session::get('cart') ?? [];

        if ($productId && isset($cart[$productId])) {
            if ($quantity < 1) {
                unset($cart[$productId]); 
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        Session::set('cart', $cart);
        echo json_encode(['success' => true]);
        exit;
    }
}

// Remove item
public function removeItem()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['product_id'] ?? null;
        $cart = Session::get('cart') ?? [];

        if ($productId && isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        Session::set('cart', $cart);
        echo json_encode(['success' => true]);
        exit;
    }
}

// Show Product Payment Page
public function showPaymentPage()
{
    if (!Session::has('user_id')) {
        Session::set('redirect_after_login', '/cart');
        header("Location: /login");
        exit;
    }

    $cart = Session::get('cart') ?? [];
    if (empty($cart)) {
        header("Location: /cart");
        exit;
    }

    // Calculate totals and subscription discount
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'] + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];
    }

    // Fetch subscription discount
    $discountPercent = 0;
    $discountAmount = 0;
    $finalTotal = $subtotal;

    $subscriptionModel = new \App\Models\Subscription();
    $subscription = $subscriptionModel->getActiveSubscription(Session::get('user_id'));
    
    if ($subscription && isset($subscription['discount_percent']) && $subscription['discount_percent'] > 0) {
        $discountPercent = (float)$subscription['discount_percent'];
        $discountAmount = $subtotal * ($discountPercent / 100);
        $finalTotal = $subtotal - $discountAmount;
    }

    $stripePublishableKey = \App\Helpers\StripeConfig::getPublishableKey();

    require APP_ROOT . '/app/Views/cart/product_payment.php';
}

// Process Product Payment via Stripe
public function processPayment()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /cart');
        exit;
    }

    if (!Session::has('user_id')) {
        header('Location: /login');
        exit;
    }

    $token = $_POST['stripeToken'] ?? null;
    if (!$token) {
        Session::set('error', 'Payment failed. Please try again.');
        header('Location: /cart');
        exit;
    }

    $cart = Session::get('cart') ?? [];
    if (empty($cart)) {
        Session::set('error', 'Cart is empty.');
        header('Location: /cart');
        exit;
    }

    // Calculate totals
    $subtotal = $taxAmount = 0;
    foreach ($cart as $item) {
        $lineTotal = $item['price'] * $item['quantity'];
        $lineTax = $lineTotal * $item['tax_percent'] / 100;
        $subtotal += $lineTotal;
        $taxAmount += $lineTax;
    }
    $totalBeforeDiscount = $subtotal + $taxAmount;
    $taxRate = $subtotal > 0 ? ($taxAmount / $subtotal) * 100 : 0;

    // Apply subscription discount
    $discountPercent = 0;
    $discountAmount = 0;
    $totalAmount = $totalBeforeDiscount;

    $subscriptionModel = new \App\Models\Subscription();
    $subscription = $subscriptionModel->getActiveSubscription(Session::get('user_id'));
    
    if ($subscription && isset($subscription['discount_percent']) && $subscription['discount_percent'] > 0) {
        $discountPercent = (float)$subscription['discount_percent'];
        $discountAmount = $totalBeforeDiscount * ($discountPercent / 100);
        $totalAmount = $totalBeforeDiscount - $discountAmount;
    }

    // Get payment amount (for partial payments)
    $paymentAmount = (float)($_POST['payment_amount'] ?? $totalAmount);
    if ($paymentAmount <= 0) {
        $paymentAmount = $totalAmount;
    }
    if ($paymentAmount > $totalAmount) {
        $paymentAmount = $totalAmount;
    }

    $isPartialPayment = $paymentAmount < $totalAmount;

    // Initialize Stripe
    \App\Helpers\StripeConfig::init();

    try {
        // Create Stripe charge with the payment amount
        $charge = \Stripe\Charge::create([
            'amount' => (int)($paymentAmount * 100), // Convert to paise
            'currency' =>'usd',
            'description' => 'Product Purchase' . ($isPartialPayment ? ' (Partial Payment)' : '') . ($discountPercent > 0 ? ' (Subscription Discount: ' . $discountPercent . '%)' : ''),
            'source' => $token,
            'metadata' => [
                'user_id' => Session::get('user_id'),
                'type' => 'product_purchase',
                'discount_percent' => $discountPercent,
                'original_amount' => $totalAmount,
                'payment_type' => $isPartialPayment ? 'partial' : 'full'
            ]
        ]);

        // Payment successful - create invoice
        $invoiceModel = new \App\Models\Invoice();
        $itemModel = new \App\Models\InvoiceItem();

        // Determine invoice status
        $invoiceStatus = $isPartialPayment ? 'Partial' : 'Paid';
        $dueDate = $isPartialPayment ? date('Y-m-d', strtotime('+7 days')) : date('Y-m-d');

        $invoiceId = $invoiceModel->create([
            'created_by' => Session::get('user_id'),
            'client_id' => Session::get('user_id'),
            'invoice_number' => 'INV-' . date('Ymd-His'),
            'invoice_date' => date('Y-m-d'),
            'due_date' => $dueDate,
            'subtotal' => $subtotal + $taxAmount,
            'tax_type' => 'GST',
            'tax_rate' => round($taxRate, 2),
            'tax_amount' => $taxAmount,
            'discount' => $discountAmount,
            'total_amount' => $totalAmount,
            'status' => $invoiceStatus,
            'notes' => ($isPartialPayment ? 'Partial payment' : 'Paid') . ' via Stripe | Transaction ID: ' . $charge->id . ($discountPercent > 0 ? ' | Subscription Discount: ' . $discountPercent . '%' : '')
        ]);

        // Add invoice items
        foreach ($cart as $item) {
            $itemModel->addItem($invoiceId, [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity']
            ]);
        }

        // Record payment in payments table
        $paymentModel = new \App\Models\Payment();
        $paymentModel->create([
            'invoice_id' => $invoiceId,
            'user_id' => Session::get('user_id'),
            'amount' => $paymentAmount,
            'payment_method' => 'stripe',
            'transaction_id' => $charge->id,
            'status' => 'completed',
            'notes' => $isPartialPayment ? 'Partial payment via Stripe' : 'Full payment via Stripe'
        ]);

        // Update invoice amount_paid
        $invoiceModel->updateAmountPaid($invoiceId, $paymentAmount);

        // Send invoice email to user
        \App\Helpers\Mailer::sendInvoiceEmail(Session::get('user_id'), $invoiceId);

        // Clear cart
        Session::remove('cart');

        if ($isPartialPayment) {
            $remainingAmount = $totalAmount - $paymentAmount;
            Session::set('success', 'Partial payment of ₹' . number_format($paymentAmount, 2) . ' successful! Remaining ₹' . number_format($remainingAmount, 2) . ' is due on the invoice.');
        } else {
            Session::set('success', 'Payment successful! Your order has been placed.');
        }
        header("Location: /");
        exit;

    } catch (\Stripe\Exception\CardException $e) {
        Session::set('error', 'Card declined: ' . $e->getMessage());
        header('Location: /cart/payment');
        exit;
    } catch (\Exception $e) {
        Session::set('error', 'Payment failed: ' . $e->getMessage());
        header('Location: /cart/payment');
        exit;
    }
}

}
