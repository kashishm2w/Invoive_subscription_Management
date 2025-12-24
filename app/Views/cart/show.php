<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/products.css">
    <a href="javascript:history.back()" class="btn-back">&#8592; Back</a>

<h1>Your Cart</h1>

<?php if (!empty($cart)): ?>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Poster</th>
            <th>Name</th>
            <th>Price (₹)</th>
            <th>Tax %</th>
            <th>Quantity</th>
            <th>Total (₹)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $grandTotal = 0; ?>
        <?php foreach ($cart as $item): ?>
            <?php
                $itemTotal =
                    $item['price'] * $item['quantity']
                    + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];

                $grandTotal += $itemTotal;
            ?>
            <tr>
                <td>
                    <?php if (!empty($item['poster'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($item['poster']) ?>" style="width:60px;">
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($item['name']) ?></td>

                <td>₹<?= number_format($item['price'], 2) ?></td>

                <td><?= $item['tax_percent'] ?>%</td>

                <td>
                    <input type="number"
                           min="1"
                           value="<?= $item['quantity'] ?>"
                           onchange="updateQty(<?= $item['id'] ?>, this.value)">
                </td>

                <td>₹<?= number_format($itemTotal, 2) ?></td>

                <td>
                    <button onclick="removeItem(<?= $item['id'] ?>)">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Grand Total: ₹<?= number_format($grandTotal, 2) ?></h3>

<form action="/invoice/create" method="POST" style="text-align:center; margin-top:20px;">
    <button type="submit" class="checkout-btn">
        Proceed to Checkout
    </button>
</form>

<?php else: ?>
<p>Your cart is empty.</p>
<?php endif; ?>

<script>
function updateQty(productId, qty) {
    fetch('/cart/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}&quantity=${qty}`
    })
    .then(() => location.reload());
}

function removeItem(productId) {
    if (!confirm('Remove item from cart?')) return;

    fetch('/cart/remove', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}`
    })
    .then(() => location.reload());
}
</script>

