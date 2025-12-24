<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/show.css">

<div class="product-detail">

    <h2><?= htmlspecialchars($product['name']) ?></h2>

    <?php if (!empty($product['poster'])): ?>
        <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>"
            style="width:200px; margin-bottom:15px;">
    <?php endif; ?>

    <p><strong>Description:</strong></p>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <?php
    $price = (float)$product['price'];
    $tax   = (float)$product['tax_percent'];
    $total = $price + ($price * $tax / 100);
    ?>
    <p><strong></strong> ₹<?= number_format($total, 2) ?></p>

    <?php if (\App\Helpers\Session::get('role') !== 'admin'): ?>
        <div style="margin-top:15px;">
            <label>Quantity:</label>
            <input type="number"
                id="qty-<?= $product['id'] ?>"
                value="1"
                min="1"
                max="<?= max(1, (int)$product['quantity']) ?>">

            <button onclick="addToCart(<?= $product['id'] ?>)">
                Add to Cart
            </button>
        </div>
         <?php else: ?>
        <a href="/dashboard/products/edit?id=<?= $product['id'] ?>">Edit Product</a>
    <?php endif; ?>

        <br>
        <a href="/products">← Back to Products</a>
</div>

<script>
    function addToCart(productId) {
        let qty = document.getElementById('qty-' + productId).value;

        fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&quantity=${qty}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Added to cart!');
                } else {
                    alert(data.error);
                }
            });
    }
</script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
