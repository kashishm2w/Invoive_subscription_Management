<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Mpdf\Mpdf;
use \App\Models\User;
use \App\Models\Company;
use App\Helpers\Mailer;

Session::start();

class InvoiceController
{
    private Invoice $invoiceModel;
    private InvoiceItem $itemModel;

    public function __construct()
    {
        Session::start();
        $this->invoiceModel = new Invoice();
        $this->itemModel = new InvoiceItem();
    }

    public function show()
    {
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }

        $invoiceId = (int)($_GET['id'] ?? 0);
        if (!$invoiceId) {
            header("Location: /dashboard");
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);

        if (!$invoice) {
            header("Location: /dashboard");
            exit;
        }

        $role   = Session::get('role');
        $userId = Session::get('user_id');

        if ($role !== 'admin' && $invoice['created_by'] !== $userId) {
            header("HTTP/1.1 403 Forbidden");
            exit('Unauthorized access');
        }

        $userModel = new User();
        $client = $userModel->getById($invoice['client_id']);

        $companyModel = new Company();
        $company = $companyModel->getByUserId($invoice['created_by']);
        
        // Fallback to admin/default company if user doesn't have company set
        if (!$company) {
            $company = $companyModel->getFirst();
        }

        $items = $this->itemModel->getByInvoice($invoiceId);
        // Calculate grand total dynamically
        $grandTotal = 0;
        foreach ($items as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $lineTax   = $lineTotal * ($invoice['tax_rate'] / 100);
            $grandTotal += $lineTotal + $lineTax;
        }

        require APP_ROOT . '/app/Views/invoice/show.php';
    }

    // Create Invoice from cart (like Cart checkout)
    public function create()
    {
        if (!Session::has('user_id')) {
            // ALWAYS go back to cart after login
            Session::set('redirect_after_login', '/cart');
            header("Location: /login");
            exit;
        }

        $cart = Session::get('cart') ?? [];
        if (empty($cart)) {
            header("Location: /cart");
            exit;
        }

        $subtotal = $taxAmount = 0;
        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $lineTax   = $lineTotal * $item['tax_percent'] / 100;
            $subtotal += $lineTotal;
            $taxAmount += $lineTax;
        }

        $totalAmount = $subtotal + $taxAmount;
        $taxRate = $subtotal > 0 ? ($taxAmount / $subtotal) * 100 : 0;

        $allowedStatus = ['pending', 'unpaid', 'paid', 'overdue'];
        $status = 'unpaid';
        if (!in_array($status, $allowedStatus)) {
            $status = 'pending';
        }

        $invoiceId = $this->invoiceModel->create([
            'created_by'=> Session::get('user_id'),
            'client_id' => Session::get('user_id'),
            'invoice_number'=> 'INV-' . date('Ymd-His'),
            'invoice_date'=> date('Y-m-d'),
            'due_date'=> date('Y-m-d', strtotime('+7 days')),
            'subtotal'=> $subtotal,
            'tax_type'=> 'GST',
            'tax_rate'=> round($taxRate, 2),
            'tax_amount'=> $taxAmount,
            'discount'=> 0,
            'total_amount'=> $totalAmount,
            'status'=> $status,
            'notes' => 'Generated from cart'
        ]);


        foreach ($cart as $item) {
            $this->itemModel->addItem($invoiceId, [
                'name'=> $item['name'],
                'quantity'=> $item['quantity'],
                'price'=> $item['price'],
                'total'=> $item['price'] * $item['quantity']
            ]);
        }

        // Send invoice email to user for COD orders
        \App\Helpers\Mailer::sendInvoiceEmail(Session::get('user_id'), $invoiceId);

        Session::remove('cart');
        header("Location: /invoice/show?id=" . $invoiceId);
        exit;
    }
    public function myInvoices()
    {
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }

        $userId = Session::get('user_id');
        $role   = Session::get('role');

        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        // Update overdue statuses before fetching
        $this->invoiceModel->updateOverdueStatuses();

        if ($role !== 'admin') {
            $allInvoices = $this->invoiceModel->getAllWithUsers();
        } else {
            $allInvoices = $this->invoiceModel->getAllInvoicesWithDetails();
        }

        $totalItems = count($allInvoices);

        $invoices = array_slice($allInvoices, $offset, $limit);

        $pagination = [
            'total'=> $totalItems,
            'per_page'=> $limit,
            'current_page'=> $currentPage,
            'total_pages'=> ceil($totalItems / $limit),
        ];

        require APP_ROOT . '/app/Views/user/my_invoices.php';
    }

    public function fetchFilteredInvoices()
    {
        $status = $_GET['status'] ?? '';
        $invoiceModel = new Invoice();

        $invoices = $invoiceModel->getAllWithUsers($_SESSION['user_id']); // fetch all invoices

        $filtered = [];
        $today = strtotime(date('Y-m-d'));

        foreach ($invoices as $invoice) {
            $invoiceStatus = strtolower($invoice['status']);
            $dueDate = strtotime($invoice['due_date']);

            if ($status === 'paid' && $invoiceStatus === 'paid') {
                $filtered[] = $invoice;
            } elseif ($status === 'unpaid' && $invoiceStatus !== 'paid' && $dueDate >= $today) {
                $filtered[] = $invoice;
            } elseif ($status === 'overdue' && $invoiceStatus !== 'paid' && $dueDate < $today) {
                $filtered[] = $invoice;
            } elseif ($status === '') {
                $filtered[] = $invoice; // all
            }
        }

        // Return JSON
        header('Content-Type: application/json');
        echo json_encode($filtered);
    }
    public function trackInvoices()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header("Location: /login");
            exit;
        }

        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        // Update overdue statuses before fetching
        $this->invoiceModel->updateOverdueStatuses();

        $allInvoices = $this->invoiceModel->getAllInvoicesWithDetails();

        $totalItems = count($allInvoices);
        $invoices = array_slice($allInvoices, $offset, $limit);
        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $currentPage,
            'total_pages' => ceil($totalItems / $limit),
        ];

        require APP_ROOT . '/app/Views/admin/track_invoices.php';
    }



    public function pdf()
    {
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }

        $invoiceId = (int)($_GET['id'] ?? 0);
        if (!$invoiceId) {
            header("Location: /dashboard");
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);
        if (!$invoice) {
            header("Location: /dashboard");
            exit;
        }

        $role   = Session::get('role');
        $userId = Session::get('user_id');

        if ($role !== 'admin' && $invoice['created_by'] !== $userId) {
            header("HTTP/1.1 403 Forbidden");
            exit('Unauthorized access');
        }

        $items = $this->itemModel->getByInvoice($invoiceId);

        $userModel = new \App\Models\User();
        $client = $userModel->getById($invoice['client_id']);

        if (!$client) {
            $client = [
                'name' => 'Customer',
                'email' => '',
                'address' => ''
            ];
        }

        // Fetch company data from database
        $companyModel = new Company();
        $company = $companyModel->getByUserId($invoice['created_by']);
        
        // Fallback to admin/default company if user doesn't have company set
        if (!$company) {
            $company = $companyModel->getFirst();
        }
        
        // Fallback if no company set at all
        if (!$company) {
            $company = [
                'company_name' => 'Invoice and Sub',
                'email' => '',
                'phone' => '',
                'address' => '',
                'tax_number' => ''
            ];
        }

        ob_start();
        require APP_ROOT . '/app/Views/invoice/pdf.php';
        $html = ob_get_clean();

        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 15,
            'margin_bottom' => 20,
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output(
            'Invoice-' . $invoice['invoice_number'] . '.pdf',
            'D'
        );
    }

    public function sendEmail()
    {
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }

        $invoiceId = (int)($_GET['id'] ?? 0);
        if (!$invoiceId) {
            header("Location: /dashboard");
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);

        if (!$invoice) {
            header("Location: /dashboard");
            exit;
        }

        $currentUserId   = Session::get('user_id');
        $currentUserRole = Session::get('role');

        if ($currentUserRole !== 'admin' && $invoice['created_by'] !== $currentUserId) {
            exit('Unauthorized');
        }

        $items = $this->itemModel->getByInvoice($invoiceId);

        if ($currentUserRole !== 'admin') {
            $userModel = new User();
            $client = $userModel->getById($invoice['client_id']);
            
            if (!$client) {
                Session::set('error', 'Client not found');
                header("Location: /invoice/show?id=" . $invoiceId);
                exit;
            }
            $clientEmail = $client['email'];
            $clientName  = $client['name'] ?? 'Customer';
        } else {
            $clientEmail = Session::get('email');
            $clientName  = Session::get('name') ?? 'Customer';
        }

        if (!$clientEmail) {
            Session::set('error', 'Client email not found');
            header("Location: /invoice/show?id=" . $invoiceId);
            exit;
        }

        // Fetch company data from database
        $companyModel = new Company();
        $company = $companyModel->getByUserId($invoice['created_by']);
        
        // Fallback if no company set
        if (!$company) {
            $company = [
                'company_name' => 'Invoice and Sub',
                'email' => '',
                'phone' => '',
                'address' => '',
                'tax_number' => ''
            ];
        }

        ob_start();
        require APP_ROOT . '/app/Views/invoice/email_template.php';
        $emailBody = ob_get_clean();

        $sent = \App\Helpers\Mailer::send(
            $clientEmail,
            $clientName,
            'Invoice ' . $invoice['invoice_number'],
            $emailBody
        );

        if ($sent) {
            Session::set('success', 'Invoice emailed successfully!');
        } else {
            Session::set('error', 'Failed to send email. Please try again.');
        }

        header("Location: /invoice/show?id=" . $invoiceId);
        exit;
    }

    /**
     * AJAX: Fetch filtered invoices for admin
     */
    public function fetchFilteredInvoicesAdmin()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Update overdue statuses
        $this->invoiceModel->updateOverdueStatuses();

        $filters = [
            'invoice_number' => $_GET['invoice_number'] ?? '',
            'email' => $_GET['email'] ?? '',
            'status' => $_GET['status'] ?? '',
        ];

        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $allInvoices = $this->invoiceModel->getFilteredInvoices($filters);
        $totalItems = count($allInvoices);

        $invoices = array_slice($allInvoices, $offset, $limit);

        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $currentPage,
            'total_pages' => ceil($totalItems / $limit),
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'invoices' => $invoices,
            'pagination' => $pagination
        ]);
    }

    /**
     * Show payment page for invoice
     */
    public function showPaymentPage()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $invoiceId = (int)($_GET['id'] ?? 0);
        if (!$invoiceId) {
            Session::set('error', 'Invoice not found');
            header('Location: /my_invoices');
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);

        if (!$invoice) {
            Session::set('error', 'Invoice not found');
            header('Location: /my_invoices');
            exit;
        }

        // Check if user owns this invoice
        $userId = Session::get('user_id');
        if ($invoice['created_by'] !== $userId && Session::get('role') !== 'admin') {
            Session::set('error', 'Unauthorized');
            header('Location: /my_invoices');
            exit;
        }

        // Check if already paid
        if (strtolower($invoice['status']) === 'paid') {
            Session::set('error', 'This invoice is already paid');
            header('Location: /my_invoices');
            exit;
        }

        $items = $this->itemModel->getByInvoice($invoiceId);
        $stripePublishableKey = \App\Helpers\StripeConfig::getPublishableKey();

        require APP_ROOT . '/app/Views/invoice/invoice_payment.php';
    }

    /**
     * Process invoice payment
     */
    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /my_invoices');
            exit;
        }

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $invoiceId = (int)($_POST['invoice_id'] ?? 0);
        $token = $_POST['stripeToken'] ?? null;

        if (!$invoiceId || !$token) {
            Session::set('error', 'Payment failed. Please try again.');
            header('Location: /my_invoices');
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);

        if (!$invoice) {
            Session::set('error', 'Invoice not found');
            header('Location: /my_invoices');
            exit;
        }

        // Check ownership
        $userId = Session::get('user_id');
        if ($invoice['created_by'] !== $userId && Session::get('role') !== 'admin') {
            Session::set('error', 'Unauthorized');
            header('Location: /my_invoices');
            exit;
        }

        // Initialize Stripe
        \App\Helpers\StripeConfig::init();

        try {
            // Create Stripe charge
            $charge = \Stripe\Charge::create([
                'amount' => (int)($invoice['total_amount'] * 100), // Convert to paise
                'currency' => 'inr',
                'description' => 'Invoice Payment: ' . $invoice['invoice_number'],
                'source' => $token,
                'metadata' => [
                    'user_id' => $userId,
                    'invoice_id' => $invoiceId,
                    'invoice_number' => $invoice['invoice_number']
                ]
            ]);

            // Payment successful - update invoice status
            $this->invoiceModel->updateStatus($invoiceId, 'Paid');

            Session::set('success', 'Payment successful! Invoice has been marked as paid.');
            header('Location: /my_invoices');
            exit;

        } catch (\Stripe\Exception\CardException $e) {
            Session::set('error', 'Card declined: ' . $e->getMessage());
            header('Location: /invoice/pay?id=' . $invoiceId);
            exit;
        } catch (\Exception $e) {
            Session::set('error', 'Payment failed: ' . $e->getMessage());
            header('Location: /invoice/pay?id=' . $invoiceId);
            exit;
        }
    }
}
