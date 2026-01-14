<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<?php
// Set default values for discount variables if not set by controller
$discountPercent = $discountPercent ?? 0;
$discountAmount = $discountAmount ?? 0;
$finalTotal = $finalTotal ?? 0;
$grandTotal = 0;
?>
<link rel="stylesheet" href="/assets/css/products.css">
    <a href="/products" class="btn-back">&#8592; Back</a>

<h2>Your Cart</h2>

<?php if (!empty($cart)): ?>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Poster</th>
            <th>Name</th>
            <th>Price (&#36;)</th>
            <th>Tax %</th>
            <th>Quantity</th>
            <th>Total (&#36;)</th>
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
            <tr data-product-id="<?= $item['id'] ?>" 
                data-price="<?= $item['price'] ?>" 
                data-tax="<?= $item['tax_percent'] ?>">
                <td>
                    <?php if (!empty($item['poster'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($item['poster']) ?>" style="width:60px;">
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($item['name']) ?></td>

                <td>&#36;<?= number_format($item['price'], 2) ?></td>

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
                    <span class="stock-error" id="error-<?= $item['id'] ?>"></span>
                </td>

                <td id="item-total-<?= $item['id'] ?>">&#36;<?= number_format($itemTotal, 2) ?></td>

                <td>
                    <button onclick="removeItem(<?= $item['id'] ?>)">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Recalculate discount amount and final total based on view's grandTotal
if ($discountPercent > 0) {
    $discountAmount = $grandTotal * ($discountPercent / 100);
    $finalTotal = $grandTotal - $discountAmount;
} else {
    $discountAmount = 0;
    $finalTotal = $grandTotal;
}
?>

<!-- Cart Totals Section -->
<div class="cart-totals" data-discount-percent="<?= $discountPercent ?>" id="cart-totals">
    <div class="totals-row subtotal-row">
        <span>Subtotal:</span>
        <span>&#36;<span id="subtotal"><?= number_format($grandTotal, 2) ?></span></span>
    </div>
    
    <?php if ($discountPercent > 0): ?>
    <div class="totals-row discount-row">
        <span>Subscription Discount (<?= $discountPercent ?>%):</span>
        <span class="discount-amount">-&#36;<span id="discount-amount"><?= number_format($discountAmount, 2) ?></span></span>
    </div>
    <?php endif; ?>
    
    <div class="totals-row final-total-row">
        <span><strong>Total to Pay:</strong></span>
        <span><strong>&#36;<span id="final-total"><?= number_format($finalTotal, 2) ?></span></strong></span>
    </div>
</div>

<div class="checkout-container">
<?php if (\App\Helpers\Session::has('user_id')): ?>
<button class="checkout-btn" onclick="openPaymentModal()">Proceed to Buy</button>
<?php else: ?>
<button class="checkout-btn" onclick="openLoginModal()">Proceed to Buy</button>
<?php endif; ?>
</div>

<!-- Payment Options Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content payment-modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h2>Choose Payment Method</h2>
        
        <!-- Selected Address Display -->
        <div id="selectedAddressSection" class="address-section" style="display: none;">
            <h4>Delivery Address</h4>
            <div id="selectedAddressCard" class="address-card selected">
                <!-- Address will be populated here -->
            </div>
        </div>

        <!-- Address Actions -->
        <div class="address-actions">
            <button type="button" id="addAddressBtn" class="address-action-btn" onclick="openAddAddressModal()">
                <span class="action-icon">+</span> Add Address
            </button>
            <button type="button" id="chooseAddressBtn" class="address-action-btn" onclick="openChooseAddressModal()">
                <span class="action-icon"></span> Choose Address
            </button>
        </div>

        <!-- No Address Warning -->
        <div id="noAddressWarning" class="no-address-warning" style="display: none;">
            <span class="warning-icon"></span>
            Please add a delivery address to continue
        </div>
        
        <div class="payment-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>&#36;<?= number_format($grandTotal, 2) ?></span>
            </div>
            <?php if ($discountPercent > 0): ?>
            <div class="summary-row discount">
                <span>Subscription Discount (<?= $discountPercent ?>%):</span>
                <span class="discount-value">-&#36;<?= number_format($discountAmount, 2) ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row total">
                <span><strong>Amount to Pay:</strong></span>
                <span><strong>&#36;<?= number_format($finalTotal, 2) ?></strong></span>
            </div>
        </div>
        
        <div class="payment-options">
            <a href="javascript:void(0)" onclick="proceedToPayment('online')" class="payment-option-btn pay-now-btn" id="payNowBtn">
                <span class="payment-icon">
                    <img src="/assets/images/icons/money.png" alt="Pay now" class="icon">
                </span>
                <span class="payment-text">
                    <strong>Pay Now</strong>
                    <small>Secure online payment via card</small>
                </span>
            </a>
            
            <form id="codForm" action="/invoice/create" method="POST" style="width: 100%;">
                <input type="hidden" name="payment_method" value="cod">
                <input type="hidden" name="address_id" id="codAddressId" value="">
                <button type="button" onclick="proceedToPayment('cod')" class="payment-option-btn cod-btn" id="codBtn">
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

<!-- Add Address Modal -->
<div id="addAddressModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddAddressModal()">&times;</span>
        <h2>Add New Address</h2>
        
        <form id="addAddressForm" class="address-form">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required>
                <span class="error-msg" id="error-full_name"></span>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" maxlength="10" required>
                <span class="error-msg" id="error-phone"></span>
            </div>
            
            <div class="form-group">
                <label for="address">Address *</label>
                <textarea id="address" name="address" rows="3" required></textarea>
                <span class="error-msg" id="error-address"></span>
            </div>
            
            <div class="form-row">
                <div class="form-group half">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city" required>
                    <span class="error-msg" id="error-city"></span>
                </div>
                
                <div class="form-group half">
                    <label for="state">State *</label>
                    <input type="text" id="state" name="state" required>
                    <span class="error-msg" id="error-state"></span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pincode">Pincode *</label>
                <input type="text" id="pincode" name="pincode" maxlength="6" required>
                <span class="error-msg" id="error-pincode"></span>
            </div>
            
            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" id="is_default" name="is_default" value="1">
                    Set as default address
                </label>
            </div>
            
            <button type="submit" class="btn-submit">Save Address</button>
        </form>
    </div>
</div>

<!-- Choose Address Modal -->
<div id="chooseAddressModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeChooseAddressModal()">&times;</span>
        <h2>Choose Address</h2>
        
        <div id="addressList" class="address-list">
            <!-- Addresses will be populated here -->
        </div>
        
        <div id="noAddressesMsg" class="no-addresses-msg" style="display: none;">
            <p>No addresses found. Please add a new address.</p>
            <button type="button" onclick="closeChooseAddressModal(); openAddAddressModal();" class="btn-add-new">
                Add New Address
            </button>
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
<script src="/assets/js/cart_show.js"></script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
