<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;

// Calculate remaining amount
$amountPaid = (float)($invoice['amount_paid'] ?? 0);
$totalAmount = (float)$invoice['total_amount'];
$remainingAmount = $totalAmount - $amountPaid;

// Get previous payments for this invoice
$paymentModel = new \App\Models\Payment();
$previousPayments = $paymentModel->getByInvoice($invoice['id']);
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
                            <div class="item-price">&#36;<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="payment-breakdown">
                <div class="breakdown-row">
                    <span>Total Amount</span>
                    <strong>&#36;<?= number_format($totalAmount, 2) ?></strong>
                </div>
                <?php if ($amountPaid > 0): ?>
                <div class="breakdown-row paid">
                    <span>Amount Paid</span>
                    <strong class="text-success">-&#36;<?= number_format($amountPaid, 2) ?></strong>
                </div>
                <?php endif; ?>
                <div class="breakdown-row remaining">
                    <span><strong>Remaining Balance</strong></span>
                    <strong class="remaining-amount">&#36;<?= number_format($remainingAmount, 2) ?></strong>
                </div>
            </div>
        </div>

        <?php if (!empty($previousPayments)): ?>
        <!-- Previous Payments -->
        <div class="previous-payments">
            <h4>Payment History</h4>
            <?php foreach ($previousPayments as $payment): ?>
                <div class="payment-entry">
                    <div class="payment-date"><?= date('d M Y, h:i A', strtotime($payment['created_at'])) ?></div>
                    <div class="payment-amount">&#36;<?= number_format($payment['amount'], 2) ?></div>
                    <div class="payment-status status-<?= strtolower($payment['status']) ?>">
                        <?= ucfirst($payment['status']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

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
            
            <!-- Partial Payment Option -->
            <div class="payment-amount-section">
                <label for="payment_amount">Payment Amount (&#36;)</label>
                <input type="number" 
                       name="payment_amount" 
                       id="payment_amount"
                       value="<?= number_format($remainingAmount, 2, '.', '') ?>" 
                       min="1" 
                       max="<?= $remainingAmount ?>" 
                       step="0.01"
                       class="payment-amount-input">
                <small class="help-text">Enter amount to pay (min ₹1, max ₹<?= number_format($remainingAmount, 2) ?>)</small>
            </div>
            
            <div class="card-input-section">
                <label>Card Details</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <button type="submit" class="pay-btn" id="submit-btn">
                <span class="loading-spinner" id="spinner"></span>
                <span id="btn-text">Pay &#36;<?= number_format($remainingAmount, 2) ?></span>
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
var remainingAmount = <?= $remainingAmount ?>;

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

// Update button text when payment amount changes
var paymentInput = document.getElementById('payment_amount');
var btnText = document.getElementById('btn-text');

paymentInput.addEventListener('input', function() {
    var amount = parseFloat(this.value) || 0;
    if (amount > remainingAmount) {
        this.value = remainingAmount;
        amount = remainingAmount;
    }
    btnText.textContent = 'Pay ₹' + amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
});

var form = document.getElementById('payment-form');
var submitBtn = document.getElementById('submit-btn');
var spinner = document.getElementById('spinner');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    var amount = parseFloat(paymentInput.value) || 0;
    if (amount <= 0 || amount > remainingAmount) {
        document.getElementById('card-errors').textContent = 'Please enter a valid payment amount';
        return;
    }

    submitBtn.disabled = true;
    spinner.style.display = 'inline-block';
    btnText.textContent = 'Processing...';

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            document.getElementById('card-errors').textContent = result.error.message;
            submitBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.textContent = 'Pay ₹' + amount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
