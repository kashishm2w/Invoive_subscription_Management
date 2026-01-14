<?php require APP_ROOT . '/app/Views/layouts/header.php';

use App\Helpers\Session;

$amountPaid = $invoice['amount_paid'] ?? 0;
$remaining  = $invoice['total_amount'] - $amountPaid;

?>
<a href="/home" class="back">&#8592; Back</a>

<link rel="stylesheet" href="/assets/css/invoice.css">
<?php if (Session::has('success')): ?>
    <div class="alert alert-success auto-hide">
        <?= Session::get('success') ?>
    </div>
<?php endif; ?>
<div class="invoice-wrapper">
    <div class="invoice-header">

        <div class="invoice-info">
            <h1>Invoice</h1>
            <p><strong>Invoice No:</strong><?= htmlspecialchars($invoice['invoice_number']) ?></p>
            <p><strong>Date:</strong> <?= date('d.m.Y', strtotime($invoice['invoice_date'])) ?></p>
        </div>
    </div>

    <div class="invoice-parties">
        <div class="pay-to">
            <h3>Invoice From:</h3>
            <p><?= htmlspecialchars($company['company_name'] ?? 'Invoice and Sub') ?></p>
            <p><?= htmlspecialchars($company['address'] ?? '') ?></p>
            <?php if (!empty($company['phone'])): ?>
                <p>Phone: <?= htmlspecialchars($company['phone']) ?></p>
            <?php endif; ?>
            <p><?= htmlspecialchars($company['email'] ?? '') ?></p>
            <?php if (!empty($company['tax_number'])): ?>
                <p><strong>GST:</strong> <?= htmlspecialchars($company['tax_number']) ?></p>
            <?php endif; ?>
        </div>

        <div class="invoice-to">
            <h3>Invoice To:</h3>
            <p><?= htmlspecialchars($client['name'] ?? 'Customer Name') ?></p>
            <p><?= htmlspecialchars($client['email'] ?? '') ?></p>
        </div>
    </div>
    <div class="invoice-info">
        <p>
            <strong>Status:</strong>
            <span class="status <?= strtolower($invoice['status']) ?>">
                <?= htmlspecialchars($invoice['status']) ?>
            </span>
        </p>
    </div>
    <table class="invoice-items">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Item</th>
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
                $lineTax   = $lineTotal * ($invoice['tax_rate'] / 100);
                $total     = $lineTotal + $lineTax;
                $grandTotal += $total;
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td>&#36;<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>&#36;<?= number_format($item['price'] * $item['quantity'], 2) ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="invoice-totals">
        <table>
            <tr>
                <td>Subtotal :</td>
                <td>&#36;<?= number_format($invoice['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <td>Tax Amount :</td>
                <td>+&#36;<?= number_format($invoice['tax_amount'], 2) ?></td>
            </tr>
            <?php if (isset($invoice['discount']) && $invoice['discount'] > 0): ?>
                <tr class="discount-row">
                    <td>Subscription Discount :</td>
                    <td style="color: #27ae60;">-&#36;<?= number_format($invoice['discount'], 2) ?></td>
                </tr>
            <?php endif; ?>
            <tr class="grand-total">
                <td>Total:</td>
                <td>&#36;<?= number_format($invoice['total_amount'], 2) ?></td>
            </tr>
        </table>
    </div>
  <div class="invoice-payment">
    <h3>Payment Information:</h3>

    <p><strong>Total Amount:</strong> &#36;<?= number_format($invoice['total_amount'], 2) ?></p>

    <p><strong>Amount Paid:</strong>
        <span style="color: green;">
            &#36;<?= number_format($amountPaid, 2) ?>
        </span>
    </p>

    <?php if ($remaining > 0): ?>
        <p><strong>Balance Due:</strong>
            <span style="color: red;">
                &#36;<?= number_format($remaining, 2) ?>
            </span>
        </p>
    <?php else: ?>
        <p style="color: green;"><strong>Invoice Fully Paid</strong></p>
    <?php endif; ?>
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
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.auto-hide');
        alerts.forEach(alert => {
            alert.classList.add('hide');
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000); // 3 seconds
</script>