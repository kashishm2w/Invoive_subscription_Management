<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
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
                    <div class="item-price">&#8377;<?= number_format($itemTotal, 2) ?></div>
                </div>
            <?php endforeach; ?>
            
            <div class="order-total">
                <span>Total Amount</span>
                <strong>&#8377;<?= number_format($grandTotal, 2) ?></strong>
            </div>
        </div>

        <!-- Card Input -->
        <form action="/cart/payment/process" method="POST" id="payment-form">
            <div class="card-input-section">
                <label>Card Details</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <button type="submit" class="pay-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Pay &#8377;<?= number_format($grandTotal, 2) ?></span>
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
    
    // Update card number display
    if (event.brand && event.brand !== 'unknown') {
        // Could update brand display here
    }
});

// Handle form submission
var form = document.getElementById('payment-form');
var submitBtn = document.getElementById('submit-btn');
var spinner = document.getElementById('spinner');
var btnText = document.getElementById('btn-text');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Processing...';

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = 'Pay &#8377;<?= number_format($grandTotal, 2) ?>';
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
