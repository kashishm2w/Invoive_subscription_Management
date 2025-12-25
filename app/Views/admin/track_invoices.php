<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="/assets/css/track_invoices.css">

<div class="invoice-container">

    <a href="javascript:history.back()" class="btn-back">&#8592; Back</a>

    <h1>Track Invoices</h1>

    <?php if (!empty($invoices)): ?>

        <table class="invoice-table">
           <thead>
    <tr>
        <th>Invoice</th>
        <th>User</th>
        <th>Total Amount</th>
        <th>Date</th>
        <th>Status</th>
    </tr>
</thead>
           <tbody>
    <?php foreach ($invoices as $invoice): ?>
        <tr>
            <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>

            <td><?= htmlspecialchars($invoice['user_name']) ?></td>

            <td>₹<?= number_format($invoice['total_amount'], 2) ?></td>

            <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>

            <td>
                <span class="status <?= strtolower($invoice['status']) ?>">
                    <?= htmlspecialchars($invoice['status']) ?>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

        </table>

    <?php else: ?>
        <p class="no-data">No invoices found.</p>
    <?php endif; ?>

</div>
<div class="pagination">
    <?= $pagination->pageLinks($_GET); ?>
</div>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
