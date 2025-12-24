<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Invoice;
use App\Models\InvoiceItem;

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

    // Show Invoice (like cart view)
    public function show()
    {
        $invoiceId = (int)($_GET['id'] ?? 0);
        if (!$invoiceId) {
            header("Location: /dashboard");
            exit;
        }

        $invoice = $this->invoiceModel->getById($invoiceId);
        $items   = $this->itemModel->getByInvoice($invoiceId);

        if (!$invoice) {
            header("Location: /dashboard");
            exit;
        }

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
            'status'         => 'Draft',
            'notes'          => 'Generated from cart'
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
}
