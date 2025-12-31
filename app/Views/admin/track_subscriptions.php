<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
?>

<link rel="stylesheet" href="/assets/css/products.css">
<style>
/*  TRACK SUBSCRIPTIONS  */

.track-header {
    max-width: 1100px;
    margin: 32px auto 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.track-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

/* TABLE WRAPPER */
.product-table {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto 40px;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
    font-family: 'Inter', Arial, sans-serif;
}

/* TABLE HEADER */
.product-table thead {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
}

.product-table th {
    padding: 14px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    color: #ffffff;
    white-space: nowrap;
}

/* TABLE BODY */
.product-table td {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
    color: #374151;
    vertical-align: middle;
}

.product-table tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

.product-table tbody tr:hover {
    background-color: #eef2ff;
}

/*  STATUS BADGES  */
.status-badge {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-transform: capitalize;
    min-width: 90px;
    text-align: center;
}

/* Subscription Status */
.status-active {
    background-color: #dcfce7;
    color: #166534;
}

.status-expired {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-cancelled {
    background-color: #e5e7eb;
    color: #374151;
}

.status-pending {
    background-color: #fff7ed;
    color: #9a3412;
}

/* Payment Status */
.payment-paid {
    background-color: #dcfce7;
    color: #166534;
}

.payment-unpaid {
    background-color: #fff7ed;
    color: #9a3412;
}

.payment-overdue {
    background-color: #fee2e2;
    color: #991b1b;
}

/*  EMPTY STATE  */
.no-data {
    margin: 30px 0;
    font-size: 15px;
    color: #6b7280;
    text-align: center;
}

/*  RESPONSIVE  */
@media (max-width: 768px) {
    .track-header {
        padding: 0 16px;
    }

    .product-table {
        font-size: 13px;
    }

    .product-table th,
    .product-table td {
        padding: 10px 8px;
    }

    .status-badge {
        font-size: 11px;
        min-width: 70px;
        padding: 4px 10px;
    }
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 28px;
     margin-bottom: 20px;
}

.pagination a {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    background: #fff;
    border: 1px solid #e5e7eb;
}

.pagination a:hover {
    background: #f3f4f6;
}

.pagination a.active {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #fff;
    border-color: transparent;
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
                <th>Sr no.</th>
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
    
    <div class="pagination">
        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

