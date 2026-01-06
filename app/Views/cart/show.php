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
            <tr data-product-id="<?= $item['id'] ?>" 
                data-price="<?= $item['price'] ?>" 
                data-tax="<?= $item['tax_percent'] ?>">
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
                    <span class="stock-error" id="error-<?= $item['id'] ?>"></span>
                </td>

                <td id="item-total-<?= $item['id'] ?>">&#8377;<?= number_format($itemTotal, 2) ?></td>

                <td>
                    <button onclick="removeItem(<?= $item['id'] ?>)">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Grand Total: &#8377;<span id="grand-total"><?= number_format($grandTotal, 2) ?></span></h3>
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
            <p>Total Amount: <strong>&#8377;<?= number_format($grandTotal, 2) ?></strong></p>
          <p> Delivery charges: <strong>&#8377;75</strong>
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
            // Update totals dynamically without page reload
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            const price = parseFloat(row.dataset.price);
            const taxPercent = parseFloat(row.dataset.tax);
            
            // Calculate new item total
            const itemTotal = (price * quantity) + (price * taxPercent / 100) * quantity;
            
            // Update item total display
            const itemTotalCell = document.getElementById('item-total-' + productId);
            itemTotalCell.innerHTML = '&#8377;' + itemTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Recalculate and update grand total
            recalculateGrandTotal();
            
            // Show success feedback (optional subtle effect)
            inputElement.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                inputElement.style.backgroundColor = '';
            }, 500);
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

function recalculateGrandTotal() {
    let grandTotal = 0;
    const rows = document.querySelectorAll('tbody tr[data-product-id]');
    
    rows.forEach(row => {
        const price = parseFloat(row.dataset.price);
        const taxPercent = parseFloat(row.dataset.tax);
        const qtyInput = row.querySelector('.qty-input');
        const quantity = parseInt(qtyInput.value);
        
        const itemTotal = (price * quantity) + (price * taxPercent / 100) * quantity;
        grandTotal += itemTotal;
    });
    
    // Update grand total display
    document.getElementById('grand-total').textContent = grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Also update the modal total if it exists
    const modalTotal = document.querySelector('.payment-summary strong');
    if (modalTotal) {
        modalTotal.innerHTML = '&#8377;' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
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

// Address Management
let selectedAddress = null;
let userAddresses = [];

function openPaymentModal() {
    document.getElementById('paymentModal').style.display = 'flex';
    loadAddresses();
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function openAddAddressModal() {
    document.getElementById('addAddressModal').style.display = 'flex';
    // Clear form
    document.getElementById('addAddressForm').reset();
    clearAddressErrors();
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').style.display = 'none';
}

function openChooseAddressModal() {
    document.getElementById('chooseAddressModal').style.display = 'flex';
    loadAddressesForSelection();
}

function closeChooseAddressModal() {
    document.getElementById('chooseAddressModal').style.display = 'none';
}

// Load addresses when payment modal opens
function loadAddresses() {
    fetch('/address/list')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                userAddresses = data.addresses;
                
                if (userAddresses.length > 0) {
                    // Find default or use first
                    const defaultAddr = userAddresses.find(a => a.is_default == 1) || userAddresses[0];
                    selectAddress(defaultAddr);
                } else {
                    // No addresses
                    selectedAddress = null;
                    document.getElementById('selectedAddressSection').style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error loading addresses:', error));
}

// Display selected address
function selectAddress(address) {
    selectedAddress = address;
    
    const section = document.getElementById('selectedAddressSection');
    const card = document.getElementById('selectedAddressCard');
    
    card.innerHTML = `
        <div class="address-name">${escapeHtml(address.full_name)}</div>
        <div class="address-phone"> ${escapeHtml(address.phone)}</div>
        <div class="address-line">${escapeHtml(address.address)}</div>
        <div class="address-city">${escapeHtml(address.city)}, ${escapeHtml(address.state)} - ${escapeHtml(address.pincode)}</div>
        ${address.is_default == 1 ? '<span class="default-badge">Default</span>' : ''}
    `;
    
    section.style.display = 'block';
    document.getElementById('noAddressWarning').style.display = 'none';
    
    // Update COD form hidden field
    document.getElementById('codAddressId').value = address.id;
}

// Load addresses for selection modal
function loadAddressesForSelection() {
    const addressList = document.getElementById('addressList');
    const noAddressMsg = document.getElementById('noAddressesMsg');
    
    if (userAddresses.length === 0) {
        addressList.style.display = 'none';
        noAddressMsg.style.display = 'block';
        return;
    }
    
    noAddressMsg.style.display = 'none';
    addressList.style.display = 'block';
    
    addressList.innerHTML = userAddresses.map(addr => `
        <div class="address-item ${selectedAddress && selectedAddress.id == addr.id ? 'selected' : ''}" 
             onclick="selectAddressFromList(${addr.id})">
            <div class="address-radio">
                <input type="radio" name="selected_address" 
                       ${selectedAddress && selectedAddress.id == addr.id ? 'checked' : ''}>
            </div>
            <div class="address-details">
                <div class="address-name">${escapeHtml(addr.full_name)}</div>
                <div class="address-phone"> ${escapeHtml(addr.phone)}</div>
                <div class="address-line">${escapeHtml(addr.address)}</div>
                <div class="address-city">${escapeHtml(addr.city)}, ${escapeHtml(addr.state)} - ${escapeHtml(addr.pincode)}</div>
                ${addr.is_default == 1 ? '<span class="default-badge">Default</span>' : ''}
            </div>
        </div>
    `).join('');
}

function selectAddressFromList(addressId) {
    const address = userAddresses.find(a => a.id == addressId);
    if (address) {
        selectAddress(address);
        closeChooseAddressModal();
    }
}

// Handle Add Address Form Submit
document.getElementById('addAddressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    clearAddressErrors();
    
    const formData = new FormData(this);
    
    fetch('/address/add', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to local array
            userAddresses.unshift(data.address);
            // Select the new address
            selectAddress(data.address);
            closeAddAddressModal();
            
            // Show success
            showToast('Address added successfully!', 'success');
        } else if (data.errors) {
            // Show validation errors
            Object.keys(data.errors).forEach(field => {
                const errorEl = document.getElementById('error-' + field);
                if (errorEl) {
                    errorEl.textContent = data.errors[field];
                    errorEl.style.display = 'block';
                }
            });
        } else {
            showToast(data.error || 'Failed to add address', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Something went wrong', 'error');
    });
});

function clearAddressErrors() {
    document.querySelectorAll('.error-msg').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
}

// Payment validation
function proceedToPayment(method) {
    if (!selectedAddress) {
        // Show warning and highlight add address button
        document.getElementById('noAddressWarning').style.display = 'flex';
        
        const addBtn = document.getElementById('addAddressBtn');
        addBtn.classList.add('highlight-btn');
        setTimeout(() => addBtn.classList.remove('highlight-btn'), 2000);
        
        return;
    }
    
    if (method === 'online') {
        // Store address in session and redirect to payment page
        sessionStorage.setItem('selectedAddressId', selectedAddress.id);
        window.location.href = '/cart/payment?address_id=' + selectedAddress.id;
    } else if (method === 'cod') {
        // Submit COD form
        document.getElementById('codAddressId').value = selectedAddress.id;
        document.getElementById('codForm').submit();
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Toast notification
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
