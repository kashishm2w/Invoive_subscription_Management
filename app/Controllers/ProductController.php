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

    // Home Page with Product Cards
    public function home()
    {
        $products = $this->productModel->getAll();
        $cart = Session::get('cart') ?? [];
        $cartProductIds = array_keys($cart);
        require APP_ROOT . '/app/Views/products/home.php';
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

        // Get cart product IDs to check if product is already in cart
        $cart = Session::get('cart') ?? [];
        $cartProductIds = array_keys($cart);
        $isInCart = in_array($product['id'], $cartProductIds);

        // If AJAX request, return only the partial view for modal
        if (isset($_GET['ajax'])) {
            require APP_ROOT . '/app/Views/products/show_partial.php';
            exit;
        }

        require APP_ROOT . '/app/Views/products/show.php';
    }

    // Add Product
    public function addProductForm()
    {
        $this->checkAdmin();
        $errors = [];
        
        // If AJAX request, return only the partial form for modal
        if (isset($_GET['ajax'])) {
            require APP_ROOT . '/app/Views/admin/add_product_form.php';
            exit;
        }
        
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
    if ($name === '' || !preg_match('/^[a-zA-Z0-9 _-]{3,100}$/', $name)) {
        $errors[] = "Product name must be 3–100 characters and contain only letters, numbers, space, - or _";
    }

if ($description === ''||!preg_match('/^[a-zA-Z0-9 .,:\$]{10,1000}$/', $description)
) {
    $errors[] = "Description must be 10 & 1000 characters and may contain only letters, numbers, space, . , : $";
}
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $price) || $price <= 0) {
        $errors[] = "Price must be a valid number with up to 2 decimal places.";
    }

    if (!preg_match('/^(100(\.0{1,2})?|[0-9]{1,2}(\.\d{1,2})?)$/', $tax_percent)) {
        $errors[] = "Tax percent must be between 0 and 100.";
    }

    if (!preg_match('/^(?:[1-9][0-9]?|100)$/', $quantity)) {
        $errors[] = "Quantity must be between 1 and 100.";
    }
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

            Session::set('success', 'Product added successfully!');
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

    if (isset($_GET['ajax'])) {
        // Only return the form for modal
        require APP_ROOT . '/app/Views/admin/edit_product_form.php';
        exit;
    }

    // fallback full page
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

            Session::set('success', 'Product updated successfully!');
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
            Session::set('success', 'Product deleted successfully!');
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