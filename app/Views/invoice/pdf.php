<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice Email</title>
<link rel="stylesheet" href="/assets/css/email_pdf.css">
</head>
<body>

<div class="email-container">

    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h2><?= htmlspecialchars($company['company_name'] ?? 'Invoice and Sub') ?></h2>
            <?php if(!empty($company['address'])): ?><p><?= htmlspecialchars($company['address']) ?></p><?php endif; ?>
            <?php if(!empty($company['email'])): ?><p>Email: <?= htmlspecialchars($company['email']) ?></p><?php endif; ?>
            <?php if(!empty($company['phone'])): ?><p>Phone: <?= htmlspecialchars($company['phone']) ?></p><?php endif; ?>
            <?php if(!empty($company['tax_number'])): ?><p>GST: <?= htmlspecialchars($company['tax_number']) ?></p><?php endif; ?>
        </div>
        <div>Status:
            <?php
            $statusClass = strtolower($invoice['status']);
            ?>
            <span class="status <?= $statusClass ?>"><?= ucfirst($invoice['status']) ?></span>
        </div>
    </div>

    <!-- Invoice Info -->
    <p>
        <strong>Invoice No:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?><br>
        <strong>Invoice Date:</strong> <?= date('d M Y', strtotime($invoice['invoice_date'])) ?><br>
        <strong>Due Date:</strong> <?= date('d M Y', strtotime($invoice['due_date'])) ?>
    </p>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $index => $item): 
                $lineTotal = $item['price'] * $item['quantity'];
            ?>
            <tr>
                <td><?= $index+1 ?></td>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td class="right">&#36;<?= number_format($item['price'], 2) ?></td>
                <td class="right">&#36;<?= number_format($lineTotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Summary -->
    <?php
        $amountPaid = $invoice['amount_paid'] ?? 0;
        $balanceDue = $invoice['total_amount'] - $amountPaid;
    ?>
    <table class="summary">
        <tr>
            <td>Subtotal</td>
            <td class="right">&#36;<?= number_format($invoice['subtotal'], 2) ?></td>
        </tr>
        <?php if (!empty($invoice['discount'])): ?>
        <tr>
            <td>Discount</td>
            <td class="right" style="color: green;">-&#36;<?= number_format($invoice['discount'], 2) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="total">Total Amount</td>
            <td class="right total">&#36;<?= number_format($invoice['total_amount'], 2) ?></td>
        </tr>
        <tr>
            <td>Amount Paid</td>
            <td class="right" style="color:green;">&#36;<?= number_format($amountPaid, 2) ?></td>
        </tr>
        <tr>
            <td><strong>Balance Due</strong></td>
            <td class="right" style="color:<?= $balanceDue>0?'red':'green' ?>;">&#36;<?= number_format($balanceDue, 2) ?></td>
        </tr>
    </table>

    <div class="note">
        <?= nl2br(htmlspecialchars($invoice['notes'] ?? 'Thank you for your business.')) ?>
    </div>

</div>

</body>
</html>
