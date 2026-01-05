<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>

<link rel="stylesheet" href="/assets/css/invoice.css">

<div class="invoice-container">
    <h1>My Invoices</h1>

    <?php if (!empty($invoices)): ?>
        <table id="invoice-table" class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Total(&#8377;)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?=htmlspecialchars($invoice['invoice_number']) ?></td>
                        <td><?=date('d M Y', strtotime($invoice['invoice_date'])) ?></td>
                        <td><?=date('d M Y', strtotime($invoice['due_date'])) ?></td>
                        <td>&#8377;<?= number_format($invoice['total_amount'], 2) ?></td>
                        <td>
                            <span class="status <?= strtolower($invoice['status']) ?>">
                                <?= ucfirst($invoice['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/invoice/show?id=<?= $invoice['id'] ?>" class="btn-back">View</a>
                            <a href="/invoice/pdf?id=<?= $invoice['id'] ?>" class="btn-back">Download</a>
                            <a href="/invoice/send-email?id=<?= $invoice['id'] ?>" class="btn-back">Send Email</a>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    <?php else: ?>
        <p class="no-data">You don't have any invoices yet.</p>
    <?php endif; ?>
</div>

<style>
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 28px;
     margin-bottom: 20px;
}
.pagination a {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    background: #fff;
    border: 1px solid #e5e7eb;
}
.pagination a:hover {
    background: #f3f4f6;
}
.pagination a.active {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    border-color: transparent;
}
</style>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>