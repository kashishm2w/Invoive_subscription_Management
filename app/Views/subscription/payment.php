<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;

// Calculate discount values if not already set
$discountPercent = (float)($plan['discount_percent'] ?? 0);
$discountAmount = isset($discountAmount) ? $discountAmount : ($plan['price'] * ($discountPercent / 100));
$finalAmount = isset($finalAmount) ? $finalAmount : ($plan['price'] - $discountAmount);
$isFreeCheckout = isset($isFreeCheckout) ? $isFreeCheckout : ($finalAmount <= 0);
?>

<link rel="stylesheet" href="/assets/css/payment.css">

<div class="payment-container">
    <div class="payment-wrapper">
        <a href="/subscriptions" class="back-link">Back to Plans</a>
        
        <div class="payment-header">
            <h2><?= $isFreeCheckout ? 'Activate Free Subscription' : 'Subscribe Now' ?></h2>
        </div>

        <?php if (!$isFreeCheckout): ?>
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
        <?php endif; ?>

        <!-- Plan Summary -->
        <div class="order-summary">
            <h3>Subscription Plan</h3>

            <div class="order-item">
                <div class="item-info">
                    <div class="item-icon"></div>
                    <div>
                        <div class="item-name"><?= htmlspecialchars($plan['plan_name']) ?></div>
                        <div class="item-qty"><?= ucfirst($plan['billing_cycle']) ?> Billing</div>
                    </div>
                </div>
                <div class="item-price">&#36;<?= number_format($plan['price'], 2) ?></div>
            </div>

            <?php if ($discountAmount > 0): ?>
            <div class="order-discount">
                <span>Subscription Discount (<?= (int)$discountPercent ?>%)</span>
                <strong class="discount-value">-&#36;<?= number_format($discountAmount, 2) ?></strong>
            </div>
            <?php endif; ?>
                        
            <div class="order-total">
                <span>Total Amount</span>
                <strong>&#36;<?= number_format($finalAmount, 2) ?></strong>
            </div>
        </div>

        <?php if ($isFreeCheckout): ?>
        <!-- Free Checkout - Buy Now Button -->
        <form action="/payment/process-free" method="POST" id="free-checkout-form">
            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
            
            <div class="free-checkout-message">
                <div class="free-badge">100% Discount Applied!</div>
                <p>This subscription is completely free. Click below to activate.</p>
            </div>
            
            <button type="submit" class="pay-btn buy-now-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Buy Now - Free</span>
            </button>
        </form>
        <?php else: ?>
        <!-- Card Input -->
        <form action="/payment/process" method="POST" id="payment-form">
            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

            
            <div class="card-input-section">
                <label>Card Details</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <button type="submit" class="pay-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Pay &#36;<?= number_format($finalAmount, 2) ?></span>
            </button>
        </form>
        <?php endif; ?>
        
        <div class="security-badge">
            <?= $isFreeCheckout ? 'No payment required' : 'Secured by Stripe' ?>
        </div>
    </div>
</div>

<?php if (!$isFreeCheckout): ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
var stripe = Stripe("<?= $stripePublishableKey ?>");
var elements = stripe.elements();

var style = {
    base: {
        fontSize: '16px',
        color: '#ffffff',
        fontFamily: 'Segoe UI, system-ui, sans-serif',
        '::placeholder': { color: '#64748b' }
    },
    invalid: {
        color: '#ef4444',
        iconColor: '#ef4444'
    }
};

var card = elements.create('card', { style: style, hidePostalCode: true });
card.mount('#card-element');

card.on('change', function(event) {
    document.getElementById('card-errors').textContent =
        event.error ? event.error.message : '';
});

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
            document.getElementById('card-errors').textContent = result.error.message;
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = 'Pay &#36;<?= number_format($finalAmount, 2) ?>';
        } else {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'stripeToken';
            hiddenInput.value = result.token.id;
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
});
</script>
<?php else: ?>
<script>
// Free checkout form handling
var form = document.getElementById('free-checkout-form');
var submitBtn = document.getElementById('submit-btn');
var spinner = document.getElementById('spinner');
var btnText = document.getElementById('btn-text');

form.addEventListener('submit', function() {
    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Activating...';
});
</script>
<?php endif; ?>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

