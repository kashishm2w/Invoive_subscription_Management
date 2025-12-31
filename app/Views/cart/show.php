<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/products.css">
    <a href="/products" class="btn-back">&#8592; Back</a>

<h2>Your Cart</h2>

<?php if (!empty($cart)): ?>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Poster</th>
            <th>Name</th>
            <th>Price (&#8377;)</th>
            <th>Tax %</th>
            <th>Quantity</th>
            <th>Total (&#8377;)</th>
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

                <td>&#8377;<?= number_format($item['price'], 2) ?></td>

                <td><?= $item['tax_percent'] ?>%</td>

                <td class="quantity-cell">
                    <input type="number"
                           class="qty-input"
                           min="1"
                           max="<?= $item['available_stock'] ?>"
                           value="<?= $item['quantity'] ?>"
                           data-stock="<?= $item['available_stock'] ?>"
                           data-product-id="<?= $item['id'] ?>"
                           onchange="updateQty(<?= $item['id'] ?>, this.value, this)">
                    <span class="stock-info"><?= $item['available_stock'] ?> in stock</span>
                    <span class="stock-error" id="error-<?= $item['id'] ?>"></span>
                </td>

                <td>&#8377;<?= number_format($itemTotal, 2) ?></td>

                <td>
                    <button onclick="removeItem(<?= $item['id'] ?>)">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Grand Total: &#8377;<?= number_format($grandTotal, 2) ?></h3>
<div class="checkout-container">
<?php if (\App\Helpers\Session::has('user_id')): ?>
<button class="checkout-btn" onclick="openPaymentModal()">Proceed to Checkout</button>
<?php else: ?>
<button class="checkout-btn" onclick="openLoginModal()">Proceed to Checkout</button>
<?php endif; ?>
</div>

<!-- Payment Options Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content payment-modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h2>Choose Payment Method</h2>
        
        <div class="payment-summary">
            <p>Total Amount: <strong>&#8377;<?= number_format($grandTotal, 2) ?></strong></p>
        </div>
        
        <div class="payment-options">
            <a href="/cart/payment" class="payment-option-btn pay-now-btn">
                <span class="payment-icon">
                    <img src="/assets/images/icons/money.png" alt="Pay now" class="icon">
                </span>
                <span class="payment-text">
                    <strong>Pay Now</strong>
                    <small>Secure online payment via card</small>
                </span>
            </a>
            
            <form action="/invoice/create" method="POST" style="width: 100%;">
                <input type="hidden" name="payment_method" value="cod">
                <button type="submit" class="payment-option-btn cod-btn">
                    <span class="payment-icon">
                        <img src="/assets/images/icons/cash-on-delivery.png" alt="COD" class="icon">
                    </span>
                    <span class="payment-text">
                        <strong>Cash on Delivery</strong>
                        <small>Pay when you receive the order</small>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>


<?php else: ?>
<p>Your cart is empty.</p>
<div class="blank-cart">
    <img src="/uploads/blank-cart.png" alt="Empty cart">
</div>
<?php endif; ?>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginModal()">&times;</span>

        <h2>Login Required</h2>

       
        <?php
        $redirect = '/cart';
        require APP_ROOT . '/app/Views/auth/login_form.php';
        ?>
    </div>
</div>

<script>
function updateQty(productId, qty, inputElement) {
    const maxStock = parseInt(inputElement.dataset.stock);
    const errorSpan = document.getElementById('error-' + productId);
    let quantity = parseInt(qty);
    
    // Clear any existing error
    errorSpan.textContent = '';
    errorSpan.classList.remove('show');
    
    // Check if quantity exceeds stock
    if (quantity > maxStock) {
        // Cap at max stock
        quantity = maxStock;
        inputElement.value = maxStock;
        
        // Show inline error message
        errorSpan.textContent = 'Only ' + maxStock + ' items available';
        errorSpan.classList.add('show');
        
        // Hide message after 3 seconds
        setTimeout(() => {
            errorSpan.classList.remove('show');
        }, 3000);
        
        // Don't proceed if trying to exceed
        return;
    }
    
    if (quantity < 1) {
        quantity = 1;
        inputElement.value = 1;
    }
    
    fetch('/cart/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            // Show inline error message
            errorSpan.textContent = data.error;
            errorSpan.classList.add('show');
            inputElement.value = data.available_stock || inputElement.defaultValue;
            
            setTimeout(() => {
                errorSpan.classList.remove('show');
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        inputElement.value = inputElement.defaultValue;
    });
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
function openLoginModal() {
    document.getElementById('loginModal').style.display = 'flex';
}
function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}
function openPaymentModal() {
    document.getElementById('paymentModal').style.display = 'flex';
}
function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}
</script>
