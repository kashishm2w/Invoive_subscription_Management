<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="/assets/css/invoice.css">

<div class="invoice-container">
    <h1>My Invoices</h1>

    <?php if (!empty($invoices)): ?>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice </th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Total(₹)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>

                        <td><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></td>

                        <td><?= date('d M Y', strtotime($invoice['due_date'])) ?></td>

                        <td>₹<?= number_format($invoice['total_amount'], 2) ?></td>

                        <td>
                            <span class="status <?= strtolower($invoice['status']) ?>">
                                <?= ucfirst($invoice['status']) ?>
                            </span>
                        </td>

                        <td>
                            <a href="/invoice/show?id=<?= $invoice['id'] ?>" class="btn-back">
                                View
                            </a>

                            <!-- PDF download (add route later) -->
                            <a href="/invoice/pdf?id=<?= $invoice['id'] ?>" class="btn-back">
                                Download
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p class="no-data">You don't have any invoices yet.</p>
    <?php endif; ?>
</div>
                        