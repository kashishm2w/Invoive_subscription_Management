<?php require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
 ?>

<link rel="stylesheet" href="/assets/css/invoice.css">
<?php if (Session::has('success')): ?>
    <div class="alert alert-success">
        <?= Session::get('success') ?>
    </div>
<?php endif; ?>
<div class="invoice-wrapper">
    <div class="invoice-header">
        <!-- <div class="logo">
            <img src="/assets/images/logo.png" alt="Company Logo">
        </div> -->
        <div class="invoice-info">
            <h1>Invoice</h1>
            <p><strong>Invoice No:</strong> #<?= htmlspecialchars($invoice['invoice_number']) ?></p>
            <p><strong>Date:</strong> <?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></p>
        </div>
    </div>

    <div class="invoice-parties">
<div class="pay-to">
    <h3>Pay To:</h3>
    <p><?= htmlspecialchars($company['company_name'] ?? 'Company Name') ?></p>
    <p><?= htmlspecialchars($company['address'] ?? '') ?></p>
    <p><?= htmlspecialchars($company['email'] ?? '') ?></p>
</div>

<div class="invoice-to">
    <h3>Invoice To:</h3>
    <p><?= htmlspecialchars($client['name'] ?? 'Customer Name') ?></p>
    <p><?= htmlspecialchars($client['address'] ?? '') ?></p>
    <p><?= htmlspecialchars($client['email'] ?? '') ?></p>
</div>
    </div>

    <table class="invoice-items">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Item</th>
                <th>Description</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($items as $index => $item): ?>
                <?php
                    $lineTotal = $item['price'] * $item['quantity'];
                    $lineTax   = $lineTotal * ($invoice['tax_rate']/100);
                    $total     = $lineTotal + $lineTax;
                    $grandTotal += $total;
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                    <td>&#8377;<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>&#8377;<?= number_format($total, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="invoice-payment">
        <h3>Payment Info:</h3>
        <p><strong>Amount:</strong> &#8377;<?= number_format($grandTotal, 2) ?></p>
    </div>

    <div class="invoice-totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>&#8377;<?= number_format($invoice['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <td>Tax (<?= $invoice['tax_rate'] ?>%):</td>
                <td>+&#8377;<?= number_format($invoice['tax_amount'], 2) ?></td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td>&#8377;<?= number_format($invoice['total_amount'], 2) ?></td>
            </tr>
        </table>
    </div>

    <div class="invoice-terms">
        <h3>Terms & Conditions:</h3>
        <p><?= htmlspecialchars($invoice['notes'] ?? 'All claims must be made in writing within 30 days. Delivery dates are not guaranteed. Taxes excluded unless stated.') ?></p>
    </div>
</div>
<div class="invoice-actions">
    <a href="/invoice/pdf?id=<?= $invoice['id'] ?>" class="btn btn-download">
         Download PDF
    </a>
    <a href="/invoice/send-email?id=<?= $invoice['id'] ?>" class="btn btn-email">
         Send Email
    </a>
</div>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
