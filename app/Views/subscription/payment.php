<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/subscription.css">
<style>
.payment-container {
    max-width: 500px;
    margin: 40px auto;
    padding: 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.payment-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
}

.plan-summary {
    background: linear-gradient(135deg, #e6f9f0, #ffffff);
    border: 2px solid #28a745;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    text-align: center;
}

.plan-summary h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.plan-summary .price {
    font-size: 28px;
    font-weight: bold;
    color: #28a745;
}

.plan-summary .billing {
    font-size: 14px;
    color: #666;
}

#card-element {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    margin-bottom: 20px;
}

#card-errors {
    color: #e74c3c;
    font-size: 14px;
    margin-bottom: 15px;
    min-height: 20px;
}

.pay-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pay-btn:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.pay-btn:disabled {
    background: #95a5a6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #666;
    text-decoration: none;
}

.back-link:hover {
    color: #2c3e50;
}



.loading-spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 2px solid #fff;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<div class="payment-container">
    <h2>Complete Your Payment</h2>
    
    <div class="plan-summary">
        <h3><?= htmlspecialchars($plan['plan_name']) ?></h3>
        <div class="price">₹<?= number_format($plan['price'], 2) ?></div>
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
            <span id="btn-text">Pay ₹<?= number_format($plan['price'], 2) ?></span>
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
            btnText.textContent = 'Pay ₹<?= number_format($plan['price'], 2) ?>';
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
