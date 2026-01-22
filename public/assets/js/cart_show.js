function updateQty(productId, qty, inputElement) {
    const maxStock = parseInt(inputElement.dataset.stock) || 0;
    const errorSpan = document.getElementById('error-' + productId);

    let quantity = parseInt(qty);

    // Clear error
    errorSpan.textContent = '';
    errorSpan.classList.remove('show');

    if (isNaN(quantity) || quantity < 1) {
        quantity = 1;
        inputElement.value = 1;
    }

    // Stock limit
    if (quantity > maxStock) {
        quantity = maxStock;
        inputElement.value = maxStock;

        errorSpan.textContent = 'Only ' + maxStock + ' items available';
        errorSpan.classList.add('show');

        setTimeout(() => errorSpan.classList.remove('show'), 1500);
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);

            const price = parseFloat(row.dataset.price) || 0;
            const taxPercent = parseFloat(row.dataset.tax) || 0;

            // Safe calculation
            const itemTotal =
                (price * quantity) +
                ((price * taxPercent) / 100) * quantity;

            document.getElementById('item-total-' + productId).innerHTML =
                '&#36;' + itemTotal.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            recalculateGrandTotal();

            inputElement.style.backgroundColor = '#d4edda';
            setTimeout(() => inputElement.style.backgroundColor = '', 1500);
        } else {
            errorSpan.textContent = data.error;
            errorSpan.classList.add('show');
            inputElement.value = data.available_stock || 1;

            setTimeout(() => errorSpan.classList.remove('show'), 1500);
        }
    })
    .catch(() => {
        inputElement.value = 1;
    });
}

function recalculateGrandTotal() {
    let subtotal = 0;
    const rows = document.querySelectorAll('tbody tr[data-product-id]');

    rows.forEach(row => {
        const price = parseFloat(row.dataset.price);
        const taxPercent = parseFloat(row.dataset.tax);
        const qtyInput = row.querySelector('.qty-input');
        const quantity = parseInt(qtyInput.value);

        const itemTotal = (price * quantity) + (price * taxPercent / 100) * quantity;
        subtotal += itemTotal;
    });

    // Get discount percent from cart-totals data attribute
    const cartTotals = document.getElementById('cart-totals');
    const discountPercent = cartTotals ? parseFloat(cartTotals.dataset.discountPercent) || 0 : 0;

    // Calculate discount and final total
    const discountAmount = subtotal * (discountPercent / 100);
    const finalTotal = subtotal - discountAmount;

    // Update subtotal display
    const subtotalEl = document.getElementById('subtotal');
    if (subtotalEl) {
        subtotalEl.textContent = subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Update discount amount display (if exists)
    const discountEl = document.getElementById('discount-amount');
    if (discountEl) {
        discountEl.textContent = discountAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Update final total display
    const finalTotalEl = document.getElementById('final-total');
    if (finalTotalEl) {
        finalTotalEl.textContent = finalTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Also update the modal totals
    const modalSubtotal = document.querySelector('.payment-summary .summary-row:first-child span:last-child');
    const modalDiscountAmount = document.querySelector('.payment-summary .summary-row.discount .discount-value');
    const modalFinalTotal = document.querySelector('.payment-summary .summary-row.total span:last-child strong');

    if (modalSubtotal) {
        modalSubtotal.innerHTML = '&#36;' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Update discount amount in modal
    if (modalDiscountAmount) {
        modalDiscountAmount.innerHTML = '-&#36;' + discountAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    if (modalFinalTotal) {
        modalFinalTotal.innerHTML = '&#36;' + finalTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}

function removeItem(productId) {
    Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/cart/remove', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table without page refresh
                        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                        if (row) {
                            row.remove();
                        }

                        // Recalculate totals
                        recalculateGrandTotal();

                        // Check if cart is now empty
                        const remainingRows = document.querySelectorAll('tbody tr[data-product-id]');
                        if (remainingRows.length === 0) {
                            // Cart is empty - show empty cart message
                            const mainContent = document.querySelector('main') || document.body;
                            const table = document.querySelector('table');
                            const cartTotals = document.getElementById('cart-totals');
                            const checkoutContainer = document.querySelector('.checkout-container');

                            if (table) table.remove();
                            if (cartTotals) cartTotals.remove();
                            if (checkoutContainer) checkoutContainer.remove();

                            // Add empty cart message
                            const emptyMsg = document.createElement('div');
                            emptyMsg.innerHTML = `
                                <p>Your cart is empty.</p>
                                <div class="blank-cart">
                                    <img src="/uploads/blank-cart.png" alt="Empty cart">
                                </div>
                            `;
                            const h2 = document.querySelector('h2');
                            if (h2) {
                                h2.insertAdjacentElement('afterend', emptyMsg);
                            }
                        }

                        Swal.fire({
                            title: 'Removed!',
                            text: 'Item has been removed from your cart.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error || 'Failed to remove item.',
                            icon: 'error'
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to remove item. Please try again.',
                        icon: 'error'
                    });
                });
        }
    });
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

    // Update hidden address fields (check if elements exist)
    const codAddressId = document.getElementById('codAddressId');
    if (codAddressId) {
        codAddressId.value = address.id;
    }

    const freeAddressId = document.getElementById('freeAddressId');
    if (freeAddressId) {
        freeAddressId.value = address.id;
    }
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
document.getElementById('addAddressForm').addEventListener('submit', function (e) {
    e.preventDefault();
    clearAddressErrors();

    const formData = new FormData(this);

    fetch('/address/add', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON Parse Error:', text);
                throw new Error('Invalid server response');
            }
        })
        .then(data => {
            if (data.success) {
                userAddresses.unshift(data.address);
                selectAddress(data.address);
                closeAddAddressModal();

                // Show success with SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: 'Address added successfully!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
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
                Swal.fire({
                    title: 'Error!',
                    text: data.error || 'Failed to add address',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong: ' + error.message,
                icon: 'error'
            });
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
    } else if (method === 'free') {
        // Submit Free checkout form (100% discount)
        document.getElementById('freeAddressId').value = selectedAddress.id;
        document.getElementById('freeCheckoutForm').submit();
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
    }, 1500);
}

