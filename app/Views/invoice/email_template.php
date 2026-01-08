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
        .company-header {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .company-header h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .company-header p {
            margin: 3px 0;
            color: #666;
            font-size: 14px;
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
    <!-- Company Header -->
    <div class="company-header">
        <h3><?= htmlspecialchars($company['company_name'] ?? 'Invoice and Sub') ?></h3>
        <?php if (!empty($company['address'])): ?>
            <p><?= htmlspecialchars($company['address']) ?></p>
        <?php endif; ?>
        <?php if (!empty($company['phone'])): ?>
            <p>Phone: <?= htmlspecialchars($company['phone']) ?></p>
        <?php endif; ?>
        <?php if (!empty($company['tax_number'])): ?>
            <p>GST: <?= htmlspecialchars($company['tax_number']) ?></p>
        <?php endif; ?>
    </div>

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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        Thank you for shopping.<br>
        <strong><?= htmlspecialchars($company['company_name'] ?? 'Invoice and Sub') ?></strong>
    </p>
</div>

</body>
</html>

