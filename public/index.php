<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(APP_ROOT)->safeLoad();

// Include Session helper
require_once APP_ROOT . '/app/Helpers/Session.php';
use App\Helpers\Session;

// Start session globally
Session::start();


require_once APP_ROOT . '/app/Core/Router.php';
require_once APP_ROOT . '/app/Core/Database.php';
require_once APP_ROOT . '/app/Models/User.php';
require_once APP_ROOT . '/app/Models/Product.php';
require_once APP_ROOT . '/app/Controllers/AuthController.php';
require_once APP_ROOT . '/app/Controllers/DashboardController.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
$router = new Router();

// $router->get('/', [AuthController::class, 'showLogin']); // map root
$router->get('/', [DashboardController::class, 'index']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/dashboard/product', [DashboardController::class, 'productDetail']); 
$router->get('/dashboard/add-product', [DashboardController::class, 'addProductForm']);
$router->post('/dashboard/add-product', [DashboardController::class, 'addProduct']);
$router->get('/dashboard/products', [DashboardController::class, 'manageProducts']);
$router->get('/dashboard/products/edit', [DashboardController::class, 'editProductForm']);
$router->post('/dashboard/products/edit', [DashboardController::class, 'updateProduct']);
$router->get('/dashboard/products/delete', [DashboardController::class, 'deleteProduct']);
$router->get('/dashboard/invoices', [DashboardController::class, 'trackInvoices']);


$router->dispatch();
