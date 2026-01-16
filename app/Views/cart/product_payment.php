<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;

// Set defaults if not passed from controller
$discountPercent = $discountPercent ?? 0;
$discountAmount = $discountAmount ?? 0;
$finalTotal = $finalTotal ?? 0;
$subtotal = $subtotal ?? 0;
?>

<link rel="stylesheet" href="/assets/css/payment.css">

<div class="payment-container">
    <div class="payment-wrapper">
        <a href="/cart" class="back-link"> Back to Cart</a>
        
        <div class="payment-header">
            <h2>Complete Payment</h2>
         
        </div>

        <!-- Credit Card Display -->
        <div class="card-display">
            <div class="card-number">.... .... .... ....</div>
            <div class="card-details">
                <div class="card-holder">
                    <span>Card Holder</span>
                    <strong><?= htmlspecialchars(Session::get('name') ?? 'Your Name') ?></strong>
                </div>
                <div class="card-expiry">
                    <span>Expires</span>
                    <strong>MM/YY</strong>
                </div>
                <div class="card-brand">
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php $grandTotal = 0; ?>
            <?php foreach ($cart as $item): ?>
                <?php
                    $itemTotal = $item['price'] * $item['quantity']
                        + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];
                    $grandTotal += $itemTotal;
                ?>
                <div class="order-item">
                    <div class="item-info">
                        <div class="item-icon">&#128230;</div>
                        <div>
                            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="item-qty">Qty: <?= $item['quantity'] ?></div>
                        </div>
                    </div>
                    <div class="item-price">&#36;<?= number_format($itemTotal, 2) ?></div>
                </div>
            <?php endforeach; ?>
            
            <div class="order-subtotal">
                <span>Subtotal</span>
                <span>&#36;<?= number_format($grandTotal, 2) ?></span>
            </div>
            
            <?php if ($discountPercent > 0): ?>
            <div class="order-discount">
                <span>Subscription Discount (<?= $discountPercent ?>%)</span>
                <span class="discount-value">-&#36;<?= number_format($discountAmount, 2) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="order-total">
                <span>Total Amount</span>
                <strong>&#36;<?= number_format($finalTotal, 2) ?></strong>
            </div>
        </div>

        <!-- Card Input -->
        <form action="/cart/payment/process" method="POST" id="payment-form">
            <!-- Pass address_id for delivery address -->
            <input type="hidden" name="address_id" value="<?= htmlspecialchars($_GET['address_id'] ?? '') ?>">
            
            <!-- Partial Payment Option -->
            <div class="partial-payment-section">
                <div class="payment-type-toggle">
                    <label class="toggle-option">
                        <input type="radio" name="payment_type" value="full" checked onchange="togglePaymentType()">
                        <span>Pay Full Amount</span>
                    </label>
                    <label class="toggle-option">
                        <input type="radio" name="payment_type" value="partial" onchange="togglePaymentType()">
                        <span>Pay Partial Amount</span>
                    </label>
                </div>
                
                <div class="partial-amount-input" id="partialAmountDiv" style="display: none;">
                    <label for="payment_amount">Payment Amount (₹)</label>
                    <input type="number" 
                           name="payment_amount" 
                           id="payment_amount"
                           value="<?= number_format($finalTotal, 2, '.', '') ?>" 
                           min="1" 
                           max="<?= $finalTotal ?>" 
                           step="0.01"
                           class="payment-amount-input">
                    <small class="help-text">Min ₹1, Max ₹<?= number_format($finalTotal, 2) ?>. Remaining will be due on invoice.</small>
                </div>
            </div>
            
            <div class="card-input-section">
                <label>Card Details</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <button type="submit" class="pay-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Pay &#36;<?= number_format($finalTotal, 2) ?></span>
            </button>
        </form>
        
        <div class="security-badge">
            Secured by Stripe
        </div>
    </div>
</div>
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
var stripe = Stripe("<?= $stripePublishableKey ?>");
var elements = stripe.elements();
var totalAmount = <?= $finalTotal ?>;

var style = {
    base: {
        fontSize: '16px',
        color: '#ffffff',
        fontFamily: 'Segoe UI, system-ui, sans-serif',
        '::placeholder': {
            color: '#64748b'
        }
    },
    invalid: {
        color: '#ef4444',
        iconColor: '#ef4444'
    }
};

var card = elements.create('card', {style: style});
card.mount('#card-element');

// Update card display on input
card.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Toggle payment type
function togglePaymentType() {
    var partialDiv = document.getElementById('partialAmountDiv');
    var paymentInput = document.getElementById('payment_amount');
    var btnText = document.getElementById('btn-text');
    var isPartial = document.querySelector('input[name="payment_type"]:checked').value === 'partial';
    
    if (isPartial) {
        partialDiv.style.display = 'block';
        updateButtonText();
    } else {
        partialDiv.style.display = 'none';
        paymentInput.value = totalAmount;
        btnText.textContent = 'Pay ₹' + totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
}

// Update button text when amount changes
var paymentInput = document.getElementById('payment_amount');
var btnText = document.getElementById('btn-text');

function updateButtonText() {
    var amount = parseFloat(paymentInput.value) || 0;
    if (amount > totalAmount) {
        paymentInput.value = totalAmount;
        amount = totalAmount;
    }
    btnText.textContent = 'Pay ₹' + amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

paymentInput.addEventListener('input', updateButtonText);

// Handle form submission
var form = document.getElementById('payment-form');
var submitBtn = document.getElementById('submit-btn');
var spinner = document.getElementById('spinner');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    var amount = parseFloat(paymentInput.value) || 0;
    if (amount <= 0 || amount > totalAmount) {
        document.getElementById('card-errors').textContent = 'Please enter a valid payment amount';
        return;
    }
    
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Processing...';

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            updateButtonText();
        } else {
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', result.token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
});
</script>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
