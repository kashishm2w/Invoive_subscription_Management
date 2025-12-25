<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Product;
use App\Models\Invoice;
use App\Helpers\Pagination; 

class ProductController
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
        Session::start(); 
    }
    public function listProducts()
    {
        $products = $this->productModel->getAll(); // fetch all products
        $cart = Session::get('cart') ?? [];
        $cartProductIds = array_keys($cart);
        require APP_ROOT . '/app/Views/products/list.php'; 
    }



    // Admin Check
    private function checkAdmin()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header("Location: /dashboard"); // redirect non-admins
            exit;
        }
    }
    // Show Single Product (View Page)
    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /products");
            exit;
        }

        $product = $this->productModel->getById($id);

        if (!$product) {
            header("Location: /products");
            exit;
        }

        require APP_ROOT . '/app/Views/products/show.php';
    }

    // Add Product
    public function addProductForm()
    {
        $this->checkAdmin();
        $errors = [];
        require APP_ROOT . '/app/Views/admin/add_product.php';
    }

    // Add Product
    public function addProduct()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = trim($_POST['price'] ?? '');
            $tax_percent = trim($_POST['tax_percent'] ?? 0);
            $quantity    = trim($_POST['quantity'] ?? 1);
            $poster      = $_FILES['poster'] ?? null;
            // validation
            if ($name === '') $errors[] = "Product name is required.";
            if ($description === '') $errors[] = "Description is required.";
            if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a valid number.";
            if (!is_numeric($tax_percent) || $tax_percent < 0) $errors[] = "Tax percent must be a valid number.";
            if (!is_numeric($quantity) || $quantity < 1 || $quantity > 150) $errors[] = "Quantity must be between 1 and 200.";

            // Poster validation
            if ($poster && $poster['error'] === 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($poster['type'], $allowed_types)) {
                    $errors[] = "Poster must be a JPG, PNG, or GIF image.";
                }
                if ($poster['size'] > 2 * 1024 * 1024) { // 2MB max
                    $errors[] = "Poster image size must be less than 2MB.";
                }
            }

            // If there are errors, reload form with errors
            if (!empty($errors)) {
                require APP_ROOT . '/app/Views/admin/add_product.php';
                return;
            }

            // Handle poster upload
            $poster_name = 'default.png';
            if ($poster && $poster['error'] === 0) {
                $poster_name = time() . '_' . basename($poster['name']);
                move_uploaded_file($poster['tmp_name'], APP_ROOT . '/public/uploads/' . $poster_name);
            }

            // Save product
            try {
                $this->productModel->add([
                    'name'        => $name,
                    'description' => $description,
                    'price'       => $price,
                    'tax_percent' => $tax_percent,
                    'quantity'    => $quantity,
                    'poster'      => $poster_name
                ]);
            } catch (\Exception $e) {
                die("DB Error: " . $e->getMessage());
            }

            header("Location: /products");
            exit;
        }
    }

    // Manage Products
    public function manageProducts()
    {
        $this->checkAdmin();
        $products = $this->productModel->getAll();
        require APP_ROOT . '/app/Views/admin/manage_products.php';
    }

    // Edit Product
    public function editProductForm()
    {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /dashboard/products");
            exit;
        }

        $product = $this->productModel->getById($id);
        require APP_ROOT . '/app/Views/admin/edit_product.php';
    }

    // Update Product
    public function updateProduct()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'name'        => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price'       => trim($_POST['price'] ?? 0),
                'tax_percent' => trim($_POST['tax_percent'] ?? 0),
                'quantity'    => trim($_POST['quantity'] ?? 0),
                'poster'      => trim($_POST['poster'] ?? 'default.png'),
            ];

            $this->productModel->update($id, $data);

            header("Location: /products");
            exit;
        }
    }

    // Delete Product
    public function deleteProduct()
    {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->productModel->delete($id);
        }
        header("Location:/products");
        exit;
    }

    // Track Invoices
public function trackInvoices()
{
    $this->checkAdmin();

    $invoiceModel = new Invoice();

    //  Current page from URL
    $currentPage = (int)($_GET['page'] ?? 1);

    //  Items per page
    $limit = 10;

    //  Calculate offset
    $offset = ($currentPage - 1) * $limit;

    // Total invoices count
    $totalItems = $invoiceModel->countAll();

    //  Create pagination object
    $pagination = new Pagination($totalItems, $limit, $currentPage);

    //  Fetch paginated invoices with user names
    $invoices = $invoiceModel->getPaginatedWithUsers($limit, $offset);

    require APP_ROOT . '/app/Views/admin/track_invoices.php';
}


}