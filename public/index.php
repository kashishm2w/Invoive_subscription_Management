<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(APP_ROOT)->safeLoad();

require_once APP_ROOT . '/app/Helpers/Session.php';
use App\Helpers\Session;
Session::start();

require_once APP_ROOT . '/app/Core/Router.php';
require_once APP_ROOT . '/app/Core/Database.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\InvoiceController;

use App\Controllers\CheckoutController;

$router = new Router();
//Dashboard
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// Auth
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Products
$router->get('/products', [ProductController::class, 'listProducts']);

$router->get('/dashboard/product', [ProductController::class, 'show']);
$router->get('/dashboard/add-product', [ProductController::class, 'addProductForm']);
$router->post('/dashboard/add-product', [ProductController::class, 'addProduct']);
$router->get('/dashboard/products/edit', [ProductController::class, 'editProductForm']);
$router->post('/dashboard/products/edit', [ProductController::class, 'updateProduct']);
$router->get('/dashboard/products/delete', [ProductController::class, 'deleteProduct']);
$router->get('/dashboard/invoices', [ProductController::class, 'trackInvoices']);

// cart
$router->get('/cart', [CartController::class, 'showCart']);
$router->post('/cart/add', [CartController::class, 'addItem']);
$router->post('/cart/update', [CartController::class, 'updateItem']);
$router->post('/cart/remove', [CartController::class, 'removeItem']);

// Invoices
$router-> get('/invoice/show',[InvoiceController::class,'show']);
$router-> post('/invoice/create',[InvoiceController::class,'create']);

// // Checkout - create invoice, generate PDF, send email
// $router->post('/checkout', [CheckoutController::class, 'index']);
$router->dispatch();

