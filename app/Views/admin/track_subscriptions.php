<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/products.css">
<style>
.track-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 90%;
    margin: 20px auto;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-expired {
    background: #f8d7da;
    color: #721c24;
}

.status-cancelled {
    background: #fff3cd;
    color: #856404;
}

.status-pending {
    background: #e2e3e5;
    color: #383d41;
}

/* Payment Status Badges */
.payment-paid {
    background: #d4edda;
    color: #155724;
}

.payment-unpaid {
    background: #fff3cd;
    color: #856404;
}

.payment-overdue {
    background: #f8d7da;
    color: #721c24;
}
</style>

<div class="track-header">
    <h1>Track Subscriptions</h1>
</div>

<?php if (empty($subscriptions)): ?>
    <p style="text-align: center; color: #666;">No subscriptions found.</p>
<?php else: ?>
    <table class="product-table">
        <thead>
            <tr>
                <th>sr no.</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Price (&#8377;)</th>
                <th>Billing Cycle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Auto Renew</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; ?>
            <?php foreach ($subscriptions as $sub): ?>
                <?php
                    $statusClass = 'status-' . strtolower($sub['status']);
                    
                    // Determine payment status
                    $paymentStatus = $sub['payment_status'] ?? 'paid';
                    $paymentClass = 'payment-' . strtolower($paymentStatus);
                ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($sub['user_name']) ?></td>
                    <td><?= htmlspecialchars($sub['user_email']) ?></td>
                    <td><strong><?= htmlspecialchars($sub['plan_name']) ?></strong></td>
                    <td>&#8377;<?= number_format($sub['price'], 2) ?></td>
                    <td><?= ucfirst($sub['billing_cycle']) ?></td>
                    <td><?= date('d M Y', strtotime($sub['start_date'])) ?></td>
                    <td><?= date('d M Y', strtotime($sub['end_date'])) ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= $sub['status'] ?></span></td>
                    <td><span class="status-badge <?= $paymentClass ?>"><?= ucfirst($paymentStatus) ?></span></td>
                    <td><?= $sub['auto_renew'] ? ' Yes' : ' No' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

