
<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/track_invoices.css">

<main class="main-content">
    <div class="dashboard-header">
        <h2>Track Subscriptions</h2>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label>Email</label>
                <input type="text" id="filter_email" placeholder="Search by email...">
            </div>
            <div class="filter-group">
                <label>Plan</label>
                <select id="filter_plan">
                    <option value="">All Plans</option>
                    <?php
                    $planModel = new \App\Models\SubscriptionPlan();
                    $plans = $planModel->getAll();
                    foreach ($plans as $plan): ?>
                        <option value="<?= $plan['id'] ?>"><?= htmlspecialchars($plan['plan_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label>Billing Cycle</label>
                <select id="filter_billing_cycle">
                    <option value="">All</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="filter_status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="button" id="clear_filters" class="btn-clear">Clear</button>
        </div>
        
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Billing Cycle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="subscription-table-body">
            <?php if (!empty($subscriptions)): ?>
                <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td><?= htmlspecialchars($sub['user_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($sub['user_email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($sub['plan_name']) ?></td>
                        <td><?= ucfirst($sub['billing_cycle']) ?></td>
                        <td><?= date('d M Y', strtotime($sub['start_date'])) ?></td>
                        <td><?= date('d M Y', strtotime($sub['end_date'])) ?></td>
                        <td><span class="status <?= strtolower($sub['status']) ?>"><?= ucfirst($sub['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No subscriptions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" id="pagination-container">
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <?php 
            $currentPage = $pagination['current_page'];
            $totalPages = $pagination['total_pages'];
            $range = 2; // Number of pages to show around current page
            ?>
            
            <!-- Previous Button -->
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="nav-btn">&laquo; Previous</a>
            <?php endif; ?>
            
            <!-- First page -->
            <a href="?page=1" <?= $currentPage === 1 ? 'class="active"' : '' ?>>1</a>
            
            <!-- Ellipsis after first page if needed -->
            <?php if ($currentPage > $range + 2): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
            
            <!-- Pages around current page -->
            <?php for ($i = max(2, $currentPage - $range); $i <= min($totalPages - 1, $currentPage + $range); $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            
            <!-- Ellipsis before last page if needed -->
            <?php if ($currentPage < $totalPages - $range - 1): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
            
            <!-- Last page (if more than 1 page) -->
            <?php if ($totalPages > 1): ?>
                <a href="?page=<?= $totalPages ?>" <?= $currentPage === $totalPages ? 'class="active"' : '' ?>><?= $totalPages ?></a>
            <?php endif; ?>
            
            <!-- Next Button -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="nav-btn">Next &raquo;</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>


<script src="/assets/js/track_subscriptions.js"></script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
