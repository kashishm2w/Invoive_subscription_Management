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

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'] + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];
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
                    'id'          => $product['id'],
                    'name'        => $product['name'],
                    'price'       => $product['price'],
                    'tax_percent' => $product['tax_percent'],
                    'quantity'    => $quantity,
                    'poster'      => $product['poster'] ?? 'default.png'
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

}
