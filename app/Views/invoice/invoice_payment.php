<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/payment.css">

<div class="payment-container">
    <div class="payment-wrapper">
        <a href="/my_invoices" class="back-link">Back to My Invoices</a>
        
        <div class="payment-header">
            <h2>Pay Invoice</h2>
        </div>

        <!-- Invoice Summary -->
        <div class="order-summary">
            <h3>Invoice Details</h3>
            
            <div class="invoice-info">
                <div class="info-row">
                    <span>Invoice Number:</span>
                    <strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong>
                </div>
                <div class="info-row">
                    <span>Invoice Date:</span>
                    <strong><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></strong>
                </div>
                <div class="info-row">
                    <span>Due Date:</span>
                    <strong><?= date('d M Y', strtotime($invoice['due_date'])) ?></strong>
                </div>
            </div>

            <?php if (!empty($items)): ?>
                <div class="items-section">
                    <h4>Items</h4>
                    <?php foreach ($items as $item): ?>
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name"><?= htmlspecialchars($item['item_name']) ?></div>
                                <div class="item-qty">Qty: <?= $item['quantity'] ?></div>
                            </div>
                            <div class="item-price">&#8377;<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="order-total">
                <span>Total Amount</span>
                <strong>&#8377;<?= number_format($invoice['total_amount'], 2) ?></strong>
            </div>
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

        <!-- Card Input -->
        <form action="/invoice/pay/process" method="POST" id="payment-form">
            <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">
            
            <div class="card-input-section">
                <label>Card Details</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <button type="submit" class="pay-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Pay &#8377;<?= number_format($invoice['total_amount'], 2) ?></span>
            </button>
        </form>
        
        <div class="security-badge">
            Secured by Stripe
        </div>
    </div>
</div>

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

var card = elements.create('card', { style: style });
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
            btnText.textContent = 'Pay &#8377;<?= number_format($invoice['total_amount'], 2) ?>';
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

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
