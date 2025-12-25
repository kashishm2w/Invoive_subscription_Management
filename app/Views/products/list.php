<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/products.css">

<div class="dashboard-header">
    <h1>Product Listing</h1>

    <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
        <a href="/dashboard/add-product" class="btn-add-product">+ Add Product</a>
    <?php endif; ?>
</div>

<?php if (!empty($products)): ?>
    <table class="product-table" border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Poster</th>
                <th>Product Name</th>
                <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                    <th>Price (₹)</th>
                    <th>Tax %</th>
                <?php else: ?>
                    <th>Total Price (₹)</th>
                <?php endif; ?>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td>
                        <?php if (!empty($product['poster']) && file_exists(APP_ROOT . '/public/uploads/' . $product['poster'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-poster" style="width:60px; height:auto;">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['name']) ?></td>

                    <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                        <td><?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['tax_percent']) ?>%</td>
                    <?php else: ?>
                        <?php
                        $price = (float)$product['price'];
                        $tax   = (float)$product['tax_percent'];
                        $total = $price + ($price * $tax / 100);
                        ?>
                        <td><strong>₹<?= number_format($total, 2) ?></strong></td>
                    <?php endif; ?>

                    <td>
                        <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                            <strong><?= (int)$product['quantity'] ?></strong>
                        <?php else: ?>
                            <span style="font-size:12px;color:#555;">You have to choose</span><br>
                           <?php
$cartQty = isset($cart[$product['id']])
    ? $cart[$product['id']]['quantity']
    : 0;
?>

<input type="number"
    id="qty-<?= $product['id'] ?>"
    value="<?= $cartQty ?>"
    min="0"
    max="<?= max(1, (int)$product['quantity']) ?>"
    onchange="updateCartQty(<?= $product['id'] ?>, this.value)">

                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="/dashboard/product?id=<?= $product['id'] ?>">View</a>
                        <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                            | <a href="/dashboard/products/edit?id=<?= $product['id'] ?>">Edit</a>
                            | <a href="/dashboard/products/delete?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            <!-- <img src="/assets/images/icons/trash.png" class="icon"> -->
                            | <?php elseif (\App\Helpers\Session::get('role') !== 'admin'): ?>
    | <button 
        id="cart-btn-<?= $product['id'] ?>" 
        onclick="addToCart(<?= $product['id'] ?>)"
        <?= in_array($product['id'], $cartProductIds) ? 'disabled' : '' ?>>
        <?= in_array($product['id'], $cartProductIds) ? 'Added' : 'Add to Cart' ?>
    </button>
<?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No products found.</p>
<?php endif; ?>
<?php if (\App\Helpers\Session::get('role') !== 'admin'): ?>

<div class="button-container">
    <a href="/cart" class="view-cart">View Cart</a>
</div>
<?php endif; ?>
<script>

    function addToCart(productId) {
        let qty = document.getElementById('qty-' + productId).value;
        let btn = document.getElementById('cart-btn-' + productId);

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
                    // Change button text to "Added" and disable it
                    btn.textContent = "Added";
                    btn.disabled = true;
                    btn.style.backgroundColor = "#6c757d"; // optional: gray out
                    btn.style.cursor = "not-allowed";
                } else {
                    // Optional: show error in console or next to button
                    console.error(data.error);
                }
            })
            .catch(err => console.error('Error:', err));
    }
</script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
