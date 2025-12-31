<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .box {
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 600px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th { background: #f5f5f5; }
    </style>
</head>
<body>

<div class="box">
    <h2>Invoice <?= htmlspecialchars($invoice['invoice_number']) ?></h2>

    <p>
        <strong>Date:</strong> <?= $invoice['invoice_date'] ?><br>
        <strong>Total:</strong> &#8377;<?= number_format($invoice['total_amount'], 2) ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>&#8377;<?= number_format($item['price'], 2) ?></td>
                <td>
        <?php if (isset($item['status'])): ?>
            <span class="status <?= strtolower($item['status']) ?>">
                <?= ucfirst($item['status']) ?>
            </span>
        <?php else: ?>
            <span class="status unpaid">Unpaid</span>
        <?php endif; ?>
    </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        Thank you from shopping.<br>
        <strong>Invoice and sub</strong>
    </p>
</div>

</body>
</html>
