<?php require APP_ROOT . '/app/Views/layouts/header.php'; ?>
<link rel="stylesheet" href="/assets/css/subscription.css">
<h1>Manage Subscription Plans</h1>

<h2>Add New Plan</h2>
<form method="POST" action="/dashboard/subscription-plans">
    <label>Plan Name</label><br>
    <input type="text" name="plan_name" required><br>
    <button type="submit">Add Plan</button>
</form>

<hr>

<h2>Existing Plans</h2>
<?php if (!empty($plans)): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Billing Cycle</th>
            <th>Description</th>
            <th>Discount</th>
        </tr>
        <?php foreach ($plans as $plan): ?>
            <tr>
                <td><?= $plan['id'] ?></td>
                <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                <td>&#8377;<?= $plan['price'] ?></td>
                <td><?= ucfirst($plan['billing_cycle']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td><?= $plan['discount_percent'] ?>%</td>

            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No plans found.</p>
<?php endif; ?>

<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>
