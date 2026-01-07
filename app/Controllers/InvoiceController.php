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
            'created_by'     => Session::get('user_id'),
            'client_id'      => Session::get('user_id'),
            'invoice_number' => 'INV-' . date('Ymd-His'),
            'invoice_date'   => date('Y-m-d'),
            'due_date'       => date('Y-m-d', strtotime('+7 days')),
            'subtotal'       => $subtotal,
            'tax_type'       => 'GST',
            'tax_rate'       => round($taxRate, 2),
            'tax_amount'     => $taxAmount,
            'discount'       => 0,
            'total_amount'   => $totalAmount,
            'status'         => $status,
            'notes' => 'Generated from cart'
        ]);


        foreach ($cart as $item) {
            $this->itemModel->addItem($invoiceId, [
                'name'     => $item['name'],
                'quantity' => $item['quantity'],
                'price'    => $item['price'],
                'total'    => $item['price'] * $item['quantity']
            ]);
        }

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

        if ($role !== 'admin') {
            $allInvoices = $this->invoiceModel->getAllWithUsers();
        } else {
            $allInvoices = $this->invoiceModel->getAllInvoicesWithDetails();
        }

        $totalItems = count($allInvoices);

        $invoices = array_slice($allInvoices, $offset, $limit);

        $pagination = [
            'total'        => $totalItems,
            'per_page'     => $limit,
            'current_page' => $currentPage,
            'total_pages'  => ceil($totalItems / $limit),
        ];

        require APP_ROOT . '/app/Views/user/my_invoices.php';
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
}
