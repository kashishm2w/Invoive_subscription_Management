<?php
namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Product;

class DashboardController
{
    private Product $productModel;

    public function __construct()
    {
        // Initialize the Product model
        $this->productModel = new Product();
        Session::start(); // start session for checking user/admin
    }

    // Show all products on the dashboard
    public function index()
    {
        $products = $this->productModel->getAll();
        require APP_ROOT . '/app/Views/dashboard.php';
    }

    // ===================== Admin Check =====================
    private function checkAdmin() {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header("Location: /dashboard"); // redirect non-admins
            exit;
        }
    }

    // ===================== Add Product =====================
    public function addProductForm() {
        $this->checkAdmin();
        require APP_ROOT . '/app/Views/admin/add_product.php';
    }

    // ===================== Add Product =====================
public function addProduct() {
    $this->checkAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price'       => trim($_POST['price'] ?? 0),
            'tax_percent' => trim($_POST['tax_percent'] ?? 0),
            'quantity'    => trim($_POST['quantity'] ?? 0),
            'poster'      => trim($_POST['poster'] ?? 'default.png'), // handle file upload separately if needed
        ];

        // Validation
        $errors = [];
        if ($data['name'] === '') $errors[] = "Product name is required.";
        if (!is_numeric($data['price']) || $data['price'] <= 0) $errors[] = "Price must be valid number.";
        if (!is_numeric($data['tax_percent']) || $data['tax_percent'] < 0) $errors[] = "Tax percent must be valid number.";
        if (!is_numeric($data['quantity']) || $data['quantity'] < 0) $errors[] = "Quantity must be valid number.";

        if (!empty($errors)) {
            require APP_ROOT . '/app/Views/admin/add_product.php';
            return;
        }

        // Call model
        $this->productModel->add($data);

        header("Location: /dashboard/products");
        exit;
    }
}


    // ===================== Manage Products =====================
    public function manageProducts() {
        $this->checkAdmin();
        $products = $this->productModel->getAll();
        require APP_ROOT . '/app/Views/admin/manage_products.php';
    }

    // ===================== Edit Product =====================
    public function editProductForm() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /dashboard/products");
            exit;
        }

        $product = $this->productModel->getById($id);
        require APP_ROOT . '/app/Views/admin/edit_product.php';
    }

    // ===================== Update Product =====================
public function updateProduct() {
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

        header("Location: /dashboard/products");
        exit;
    }
}


    // ===================== Delete Product =====================
    public function deleteProduct() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->productModel->delete($id);
        }
        header("Location: /dashboard/products");
        exit;
    }

    // ===================== Track Invoices =====================
    public function trackInvoices() {
        $this->checkAdmin();
        // You can fetch invoices from your Invoice model here
        $invoices = []; // replace with actual fetch
        require APP_ROOT . '/app/Views/admin/track_invoices.php';
    }
}
