<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/products.css">
<link rel="stylesheet" href="/assets/css/payment.css">

<div class="payment-container">
    <a href="/cart" class="back-link">← Back to Cart</a>
    
    <h2>Complete Your Payment</h2>
    
    <div class="cart-summary">
        <h3>Order Summary</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php foreach ($cart as $item): ?>
                    <?php
                        $itemTotal = $item['price'] * $item['quantity']
                            + ($item['price'] * $item['tax_percent'] / 100) * $item['quantity'];
                        $grandTotal += $itemTotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>&#8377;<?= number_format($item['price'], 2) ?></td>
                        <td>&#8377;<?= number_format($itemTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Grand Total</strong></td>
                    <td><strong>&#8377;<?= number_format($grandTotal, 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <form action="/cart/payment/process" method="POST" id="payment-form">
        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #34495e;">
            Card Details
        </label>
        
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>
        
        <button type="submit" class="pay-btn" id="submit-btn">
            <span class="loading-spinner" id="spinner"></span>
            <span id="btn-text">Pay &#8377;<?= number_format($grandTotal, 2) ?></span>
        </button>
    </form>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
var stripe = Stripe("<?= $stripePublishableKey ?>");
var elements = stripe.elements();

var style = {
    base: {
        fontSize: '16px',
        color: '#32325d',
        fontFamily: 'Arial, sans-serif',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#e74c3c',
        iconColor: '#e74c3c'
    }
};

var card = elements.create('card', {style: style});
card.mount('#card-element');

// Handle real-time validation errors
card.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Handle form submission
var form = document.getElementById('payment-form');
var submitBtn = document.getElementById('submit-btn');
var spinner = document.getElementById('spinner');
var btnText = document.getElementById('btn-text');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Disable button and show loading
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Processing...';

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Show error
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            // Re-enable button
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = 'Pay &#8377;<?= number_format($grandTotal, 2) ?>';
        } else {
            // Add token to form and submit
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
