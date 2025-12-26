<?php 
require APP_ROOT . '/app/Views/layouts/header.php';
use App\Helpers\Session;
$currentSubscription = $currentSubscription ?? null;

?>


<link rel="stylesheet" href="/assets/css/subscription.css">

<h1>Subscription Plans</h1>

<?php if (Session::has('user_id') && is_array($currentSubscription)): ?>
    <div class="active-plan">
        <h3>Your Active Plan</h3>
        <p>
            <strong><?= htmlspecialchars($currentSubscription['plan_name']) ?></strong><br>
            
            ₹<?= $currentSubscription['price'] ?> /
            <?= ucfirst($currentSubscription['billing_cycle']) ?><br>
            Valid till: <?= $currentSubscription['end_date'] ?>
        </p>
    </div>

<?php elseif (
    Session::has('user_id') &&
    !$currentSubscription &&
    Session::get('role') !== 'admin'
): ?>

    <p>You currently have no subscription. Choose a plan below:</p>

<?php elseif (!Session::has('user_id')): ?>

    <p>
        Please <a href="javascript:void(0)" onclick="openLoginModal()">login</a> to subscribe.
    </p>

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
?>

<div class="plan-card <?= $isActivePlan ? 'active-plan-card' : '' ?>">

            <h3><?= htmlspecialchars($plan['plan_name']) ?></h3>
            <?php if ($isActivePlan): ?>
    <div class="active-badge">Your Active Plan</div>
<?php endif; ?>

            <p><?= htmlspecialchars($plan['description']) ?></p>
            <p class="price">
                ₹<?= $plan['price'] ?>
                <span class="billing-badge"><?= ucfirst($plan['billing_cycle']) ?></span>
            </p>

            <?php if (Session::has('user_id') && Session::get('role') !== 'admin'): ?>
                <form method="POST" action="/subscribe">
                    <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                    <label>
                        <input type="checkbox" name="auto_renew"> Auto renew
                    </label>
                    <button type="submit">Subscribe</button>
                </form>

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
        <span id="closeModal">&times;</span>
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


<script>
/*  LOGIN MODAL  */
function openLoginModal() {
    document.getElementById('loginModal').style.display = 'flex';
}
function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}

/*  PLAN MODAL  */
document.addEventListener('DOMContentLoaded', function () {

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
                    modal.style.display = 'flex';
                });
        });
    });

    document.querySelectorAll('.deletePlanBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this plan?')) {
                window.location = `/admin/plan/delete?id=${btn.dataset.id}`;
            }
        });
    });
});
</script>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
