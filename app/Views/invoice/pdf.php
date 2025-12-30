<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            margin: 0 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .no-border td {
            border: none;
            padding: 3px;
        }
    </style>
</head>

<body>

<h2>Invoice</h2>

<table class="no-border">
    <tr>
        <td>
            <strong>Invoice No:</strong> <?= $invoice['invoice_number'] ?><br>
            <strong>Date:</strong> <?= date('d M Y', strtotime($invoice['invoice_date'])) ?><br>
            <strong>Due:</strong> <?= date('d M Y', strtotime($invoice['due_date'])) ?>
        </td>
        <td class="right">
            <strong><?= htmlspecialchars($company['company_name'] ?? 'Company Name') ?></strong><br>
            <?= htmlspecialchars($company['email'] ?? '') ?><br>
            GST: <?= htmlspecialchars($company['tax_number'] ?? 'N/A') ?>
        </td>
    </tr>
</table>

<table class="no-border">
    <tr>
        <td>
            <strong>Billed To:</strong><br>
            <p><?= htmlspecialchars($client['name'] ?? 'Customer Name') ?></p>
            <p><?= htmlspecialchars($client['address'] ?? '') ?></p>
            <p><?= htmlspecialchars($client['email'] ?? '') ?></p>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Sr no.</th>
            <th>Item</th>
            <th class="right">Price</th>
            <th class="right">Qty</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $i => $item): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($item['item_name']) ?></td>
            <td class="right">&#8377;<?= number_format($item['price'], 2) ?></td>
            <td class="right"><?= $item['quantity'] ?></td>
            <td class="right">&#8377;<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table>
    <tr>
        <td class="right">Subtotal</td>
        <td class="right">&#8377;<?= number_format($invoice['subtotal'], 2) ?></td>
    </tr>
    <tr>
        <td class="right">GST (<?= $invoice['tax_rate'] ?>%)</td>
        <td class="right">&#8377;<?= number_format($invoice['tax_amount'], 2) ?></td>
    </tr>
    <tr>
        <td class="right"><strong>Grand Total</strong></td>
        <td class="right"><strong>&#8377;<?= number_format($invoice['total_amount'], 2) ?></strong></td>
    </tr>
</table>

<p>
    <strong>Status:</strong> <?= ucfirst($invoice['status']) ?><br>
    <strong>Notes:</strong> <?= nl2br(htmlspecialchars($invoice['notes'])) ?>
</p>

</body>
</html>
