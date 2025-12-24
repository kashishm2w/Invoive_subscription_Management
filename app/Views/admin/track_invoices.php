<table border="1" cellpadding="5">
    <tr>
        <th>Invoice #</th>
        <th>Client</th>
        <th>Date</th>
        <th>Status</th>
    </tr>
    <?php foreach ($invoices as $invoice): ?>
    <tr>
        <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
        <td><?= htmlspecialchars($invoice['client_name']) ?></td>
        <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>
        <td><?= htmlspecialchars($invoice['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
