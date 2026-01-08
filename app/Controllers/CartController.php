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
    $totalAmount = $subtotal + $taxAmount;
    $taxRate = $subtotal > 0 ? ($taxAmount / $subtotal) * 100 : 0;

    // Initialize Stripe
    \App\Helpers\StripeConfig::init();

    try {
        // Create Stripe charge
        $charge = \Stripe\Charge::create([
            'amount' => (int)($totalAmount * 100), // Convert to paise
            'currency' => 'inr',
            'description' => 'Product Purchase',
            'source' => $token,
            'metadata' => [
                'user_id' => Session::get('user_id'),
                'type' => 'product_purchase'
            ]
        ]);

        // Payment successful - create invoice
        $invoiceModel = new \App\Models\Invoice();
        $itemModel = new \App\Models\InvoiceItem();

        $invoiceId = $invoiceModel->create([
            'created_by' => Session::get('user_id'),
            'client_id' => Session::get('user_id'),
            'invoice_number' => 'INV-' . date('Ymd-His'),
            'invoice_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'subtotal' => $subtotal,
            'tax_type' => 'GST',
            'tax_rate' => round($taxRate, 2),
            'tax_amount' => $taxAmount,
            'discount' => 0,
            'total_amount' => $totalAmount,
            'status' => 'paid',
            'notes' => 'Paid via Stripe | Transaction ID: ' . $charge->id
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

        // Send invoice email to user
        \App\Helpers\Mailer::sendInvoiceEmail(Session::get('user_id'), $invoiceId);

        // Clear cart
        Session::remove('cart');

        Session::set('success', 'Payment successful! Your order has been placed.');
        header("Location: /invoice/show?id=" . $invoiceId);
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
