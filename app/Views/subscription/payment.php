<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/subscription.css">
<link rel="stylesheet" href="/assets/css/payment.css">

<div class="payment-container">
    <h2>Complete Your Payment</h2>
    
    <div class="plan-summary">
        <h3><?= htmlspecialchars($plan['plan_name']) ?></h3>
        <div class="price">&#8377;<?= number_format($plan['price'], 2) ?></div>
        <div class="billing"><?= ucfirst($plan['billing_cycle']) ?> Billing</div>
    </div>

    <form action="/payment/process" method="POST" id="payment-form">
        <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
        <input type="hidden" name="auto_renew" value="<?= $autoRenew ?>">
        
        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #34495e;">
            Card Details
        </label>
        
        <div id="card-element"></div>
        <div id="card-errors" role="alert"></div>
        
        <button type="submit" class="pay-btn" id="submit-btn">
            <span class="loading-spinner" id="spinner"></span>
            <span id="btn-text">Pay &#8377;<?= number_format($plan['price'], 2) ?></span>
        </button>
    </form>

    <a href="/subscriptions" class="back-link"> Back to Plans</a>

   
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
            btnText.textContent = 'Pay &#8377;<?= number_format($plan['price'], 2) ?>';
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
