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
require_once APP_ROOT . '/app/Models/Subscription.php';

// require_once APP_ROOT . '/app/Models/Product.php';
require_once APP_ROOT . '/app/Controllers/AuthController.php';
require_once APP_ROOT . '/app/Controllers/DashboardController.php';
require_once APP_ROOT . '/app/Controllers/ProductController.php';
require_once APP_ROOT . '/app/Controllers/CartController.php';
require_once APP_ROOT . '/app/Controllers/InvoiceController.php';
require_once APP_ROOT . '/app/Controllers/SubscriptionController.php';
require_once APP_ROOT . '/app/Controllers/SettingController.php';
require_once APP_ROOT . '/app/Controllers/PaymentController.php';
require_once APP_ROOT . '/app/Controllers/AddressController.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UserController;

use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\InvoiceController;
use App\Controllers\SubscriptionController;
use App\Controllers\SubscriptionPlanController;
use App\Controllers\PaymentController;

use App\Controllers\SettingController;
use App\Controllers\AddressController;

$router = new Router();
//Dashboard/*  */
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// Auth
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// user
// $router->post('/user/update-profile',[UserController::class ,'updateProfile']);

// Products
$router->get('/home', [ProductController::class, 'home']);
$router->get('/products', [ProductController::class, 'listProducts']);

$router->get('/dashboard/product', [ProductController::class, 'show']);
$router->get('/dashboard/add-product', [ProductController::class, 'addProductForm']);
$router->post('/dashboard/add-product', [ProductController::class, 'addProduct']);
$router->get('/dashboard/products/edit', [ProductController::class, 'editProductForm']);
$router->post('/dashboard/products/edit', [ProductController::class, 'updateProduct']);
$router->get('/dashboard/products/delete', [ProductController::class, 'deleteProduct']);
// $router->get('/dashboard/invoices', [ProductController::class, 'trackInvoices']);
// cart
$router->get('/cart', [CartController::class, 'showCart']);
$router->post('/cart/add', [CartController::class, 'addItem']);
$router->post('/cart/update', [CartController::class, 'updateItem']);
$router->post('/cart/remove', [CartController::class, 'removeItem']);
$router->get('/cart/payment', [CartController::class, 'showPaymentPage']);
$router->post('/cart/payment/process', [CartController::class, 'processPayment']);

// Invoices
$router-> get('/invoice/show',[InvoiceController::class,'show']);
$router-> post('/invoice/create',[InvoiceController::class,'create']);
$router->get('/my_invoices', [InvoiceController::class, 'myInvoices']);
// Admin: Track all invoices
$router->get('/track_invoices', [InvoiceController::class, 'trackInvoices']);

$router->get('/invoice/pdf', [InvoiceController::class, 'pdf']);
$router->get('/invoice/send-email',[InvoiceController::class,'sendEmail']);

//subscription
$router->get('/subscriptions', [SubscriptionController::class, 'index']);
$router->post('/subscribe', [SubscriptionController::class, 'subscribe']);
$router->post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription']);

// Payment (Stripe)
$router->get('/payment', [PaymentController::class, 'showPaymentPage']);
$router->post('/payment/process', [PaymentController::class, 'processPayment']);

// Admin: Track Subscriptions
$router->get('/track_subscriptions', [SubscriptionController::class, 'trackSubscriptions']);

$router->get('/dashboard/subscription-plans', [SubscriptionPlanController::class, 'index']);
$router->post('/dashboard/subscription-plans', [SubscriptionPlanController::class, 'create']);
$router->get('/admin/plans', [SubscriptionPlanController::class, 'index']);
$router->get('/admin/plan/get', [SubscriptionPlanController::class, 'getPlan']);
$router->post('/admin/plan/save', [SubscriptionPlanController::class, 'save']);
$router->get('/admin/plan/delete', [SubscriptionPlanController::class, 'delete']);

// setting
$router->get('/settings',         [SettingController:: class,'index']);
$router->post('/settings/update', [SettingController:: class,'update']);
$router->get('/settings/company', [SettingController::class,'company']);
$router->post('/settings/company',  [SettingController::class,'company']);

// Address routes
$router->get('/address/list', [AddressController::class, 'getAddresses']);
$router->post('/address/add', [AddressController::class, 'addAddress']);
$router->post('/address/set-default', [AddressController::class, 'setDefaultAddress']);

$router->dispatch();



