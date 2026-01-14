<?php
require APP_ROOT . '/app/Views/layouts/header.php';

use App\Helpers\Session;

$currentSubscription = $currentSubscription ?? null;
$cancelledSubscription = $cancelledSubscription ?? null;
$expiredSubscription = $expiredSubscription ?? null;

?>
<link rel="stylesheet" href="/assets/css/subscription.css">
<div class="wrapper">
    <?php if (Session::has('error')): ?>
        <div class="alert alert-error">
            <?= Session::get('error') ?>
        </div>
        <?php Session::remove('error'); ?>
    <?php endif; ?>

    <?php if (Session::has('success')): ?>
        <div class="alert alert-success auto-hide">
            <?= Session::get('success') ?>
        </div>
        <?php Session::remove('success'); ?>
    <?php endif; ?>
    <div class="containers">

        <h1>Subscription Plans</h1>
    </div>

    <?php if (Session::has('user_id') && is_array($currentSubscription)): ?>
        <?php
        // Check if subscription is expiring soon (within 7 days)
        $endDate = new DateTime($currentSubscription['end_date']);
        $today = new DateTime();
        $daysRemaining = (int)$today->diff($endDate)->format('%r %a');
        $isExpiringSoon = $daysRemaining >= 0 && $daysRemaining <= 3;
        ?>
        <div class="active-plan <?= $isExpiringSoon ? 'expiring-soon' : '' ?>">
            <h3>Your Active Plan</h3>
            <?php if ($isExpiringSoon): ?>
                <div class="expiring-warning">
                    <i class="warning-icon"></i>
                    <span>Your plan will expire in <?= $daysRemaining ?> day<?= $daysRemaining !== 1 ? 's' : '' ?>!</span>
                </div>
            <?php endif; ?>
            <p>
                <strong><?= htmlspecialchars($currentSubscription['plan_name']) ?></strong><br>

                &#36;<?= $currentSubscription['price'] ?> /
                <?= ucfirst($currentSubscription['billing_cycle']) ?><br>
                Valid till: <?= $currentSubscription['end_date'] ?>
            </p>
        </div>

    <?php elseif (Session::has('user_id') && is_array($cancelledSubscription)): ?>
        <div class="cancelled-plan">
            <h3>Your Subscription</h3>
            <span class="cancelled-badge">Cancelled</span>
            <p>
                <strong><?= htmlspecialchars($cancelledSubscription['plan_name']) ?></strong><br>
                &#36;<?= $cancelledSubscription['price'] ?> /
                <?= ucfirst($cancelledSubscription['billing_cycle']) ?><br>
                <span class="cancelled-text">This subscription has been cancelled.</span>
            </p>
        </div>

    <?php elseif (Session::has('user_id') && is_array($expiredSubscription)): ?>
        <div class="expired-plan">
            <h3>Your Subscription</h3>
            <div class="expired-warning">
                <i class="warning-icon"></i>
                <span>Your plan is expired! Please reactivate or choose a new plan.</span>
            </div>
            <p>
                <strong><?= htmlspecialchars($expiredSubscription['plan_name']) ?></strong><br>
                &#36;<?= $expiredSubscription['price'] ?> /
                <?= ucfirst($expiredSubscription['billing_cycle']) ?><br>
                <span class="expired-text">Expired on: <?= $expiredSubscription['end_date'] ?></span>
            </p>
            <form method="POST" action="/subscribe" class="reactivate-form">
                <input type="hidden" name="plan_id" value="<?= $expiredSubscription['plan_id'] ?>">
                <button type="submit" class="btn-reactivate">Reactivate Now</button>
            </form>
        </div>

    <?php elseif (
        Session::has('user_id') &&
        !$currentSubscription &&
        Session::get('role') !== 'admin'
    ): ?>

    <?php elseif (!Session::has('user_id')): ?>
    <?php endif; ?>


    <?php if (Session::get('role') === 'admin'): ?>
        <button id="addPlanBtn">Add Plan</button>
    <?php endif; ?>


    <div class="plans">
        <?php foreach ($plans as $plan): ?>
            <?php
            $isActivePlan =
                Session::has('user_id') &&
                is_array($currentSubscription) &&
                isset($currentSubscription['plan_id']) &&
                $currentSubscription['plan_id'] == $plan['id'];
            
            $isExpiredPlan =
                Session::has('user_id') &&
                is_array($expiredSubscription) &&
                isset($expiredSubscription['plan_id']) &&
                $expiredSubscription['plan_id'] == $plan['id'];
            
            $hasExpiredSubscription = Session::has('user_id') && is_array($expiredSubscription);
            ?>
            <div class="plan-card <?= $isActivePlan ? 'active-plan-card' : '' ?> <?= $isExpiredPlan ? 'expired-plan-card' : '' ?>">

                <h3><?= htmlspecialchars($plan['plan_name']) ?></h3>

                <p><?= htmlspecialchars($plan['description']) ?></p>
                <p class="price">
                    &#36;<?= $plan['price'] ?>
                    <span class="billing-badge"><?= ucfirst($plan['billing_cycle']) ?></span>
                </p>

                <?php if (Session::has('user_id') && Session::get('role') !== 'admin'): ?>
                    <?php if ($isActivePlan): ?>
                        <!-- Active plan: Show Active button -->
                        <div class="plan-actions">
                            <button class="btn-active" disabled>Active</button>
                            <button class="btn-cancel" onclick="cancelSubscription()">Cancel</button>
                        </div>

                    <?php elseif ($isExpiredPlan): ?>
                        <!-- Expired plan: Show Reactivate button -->
                        <div class="expired-plan-message">Your plan is expired!</div>
                        <form method="POST" action="/subscribe">
                            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                            <label>
                                <input type="checkbox" name="auto_renew"> Auto renew
                            </label>
                            <button type="submit" class="btn-reactivate">Reactivate</button>
                        </form>

                    <?php elseif ($hasExpiredSubscription): ?>
                        <!-- Has expired subscription but this is a different plan: Show Buy Now -->
                        <form method="POST" action="/subscribe">
                            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                            <label>
                                <input type="checkbox" name="auto_renew"> Auto renew
                            </label>
                            <button type="submit">Buy Now</button>
                        </form>

                    <?php else: ?>
                        <!-- No subscription: Show Buy Now -->
                        <form method="POST" action="/subscribe">
                            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                            <label>
                                <input type="checkbox" name="auto_renew"> Auto renew
                            </label>
                            <button type="submit">Buy Now</button>
                        </form>
                    <?php endif; ?>

                <?php elseif (!Session::has('user_id')): ?>
                    <button class="checkout-btn" onclick="openLoginModal()">Subscribe</button>
                <?php endif; ?>

                <?php if (Session::get('role') === 'admin'): ?>
                    <button class="editPlanBtn" data-id="<?= $plan['id'] ?>">Edit</button>
                    <button class="deletePlanBtn" data-id="<?= $plan['id'] ?>">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- = ADD / EDIT PLAN MODAL = -->
    <div id="planModal">
        <div>
            <span id="closeModal" class="close-btn" onclick="closePlanModal()">&times;</span>
            <h3 id="modalTitle">Add Plan</h3>

            <?php
            $redirect = '/subscriptions';
            require APP_ROOT . '/app/Views/subscription/add_plan.php';
            ?>
        </div>
    </div>

    <!-- = LOGIN MODAL = -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <h2>Login Required</h2>

            <?php
            $redirect = '/subscriptions';
            require APP_ROOT . '/app/Views/auth/login_form.php';
            ?>
        </div>
    </div>
    <?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
</div>
<script>
    /*  CANCEL SUBSCRIPTION  */
    function cancelSubscription() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you really want to cancel your subscription?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/subscription/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {
                    if (response.ok || response.redirected) {
                        Swal.fire({
                            title: 'Cancelled!',
                            text: 'Your subscription has been cancelled.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = '/subscriptions';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to cancel subscription. Please try again.',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error'
                    });
                });
            }
        });
    }

    /*  LOGIN MODAL  */
    function openLoginModal() {
        document.getElementById('loginModal').style.display = 'flex';
    }

    function closeLoginModal() {
        document.getElementById('loginModal').style.display = 'none';
    }

    function closePlanModal() {
        document.getElementById('planModal').style.display = 'none';
    }

    /*  PLAN MODAL  */
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('planModal');
        const closeModal = document.getElementById('closeModal');
        const addBtn = document.getElementById('addPlanBtn');
        const form = document.getElementById('planForm');

        addBtn?.addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = 'Add Plan';
            form.reset();
            modal.style.display = 'flex';
        });

        closeModal?.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        document.querySelectorAll('.editPlanBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(`/admin/plan/get?id=${btn.dataset.id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('modalTitle').innerText = 'Edit Plan';
                        document.getElementById('plan_id').value = data.id;
                        document.getElementById('plan_name').value = data.plan_name;
                        document.getElementById('price').value = data.price;
                        document.getElementById('billing_cycle').value = data.billing_cycle;
                        document.getElementById('description').value = data.description;
                        document.getElementById('discount_percent').value = data.discount_percent || 0;
                        modal.style.display = 'flex';
                    });
            });
        });

        document.querySelectorAll('.deletePlanBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                Swal.fire({
                    title: 'Delete Plan?',
                    text: 'Are you sure you want to delete this plan? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = `/admin/plan/delete?id=${btn.dataset.id}`;
                    }
                });
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');

        if (!alerts.length) return;

        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';

                setTimeout(() => alert.remove(), 1500);
            });
        }, 2000); // 2 seconds
    });
     setTimeout(() => {
        const alerts = document.querySelectorAll('.auto-hide');
        alerts.forEach(alert => {
            alert.classList.add('hide');
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000); // 3 seconds
</script>