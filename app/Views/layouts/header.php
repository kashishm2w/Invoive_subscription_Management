<?php
use App\Helpers\Session;
?>
<link rel="stylesheet" href="/assets/css/header_footer.css">

<header class="main-header">
    <div class="container header_content">
        <!-- Left: Logo (clickable) -->
        <a href="/home" class="header_logo">Invoice & Subscription System</a>

        <!-- Center: Navigation -->
        <nav class="header_nav">
            <ul class="nav_list">
                <li class="nav_item"><a href="/home" class="nav_link">Home</a></li>

                <?php if (Session::get('role') === 'admin'): ?>
                    <li class="nav_item"><a href="/dashboard" class="nav_link">Dashboard</a></li>
                    <li class="nav_item"><a href="/track_invoices" class="nav_link">Track Invoices</a></li>
                    <li class="nav_item"><a href="/track_subscriptions" class="nav_link">Track Subscriptions</a></li>
                <?php endif; ?>

                <?php if (Session::has('user_id') && Session::get('role') !== 'admin'): ?>
                    <li class="nav_item"><a href="/my_invoices" class="nav_link">My Invoices</a></li>
                <?php endif; ?>

                <li class="nav_item"><a href="/products" class="nav_link">Products</a></li>
                <li class="nav_item"><a href="/subscriptions" class="nav_link">Subscriptions</a></li>
                <?php if (!Session::has('user_id') || Session::get('role') !== 'admin'): ?>
                    <li class="nav_item"><a href="/cart" class="nav_link">Cart</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Right: Account -->
        <div class="header_account">
            <?php if (Session::has('user_id')): ?>
                <span>Welcome, <strong class="top-bar_username"><?= htmlspecialchars(Session::get('name')) ?></strong></span>
                <button class="account_btn"><img src="/assets/images/icons/menu.png" class="icon" alt="Menu"></button>

                <div class="account_menu">
                    <?php if (Session::get('role') !== 'admin'): ?>
                        <a href="/my_invoices" class="account_link">My Invoices</a>
                    <?php endif; ?>

                    <?php if (Session::get('role') === 'admin'): ?>
                        <a href="/dashboard/add-product" class="account_link">Add Product</a>
                        <a href="/track_invoices" class="account_link">Track Invoices</a>
                    <?php endif; ?>

                    <a href="/settings" class="account_link">Settings</a>
                    <a href="/logout" class="account_link">Logout <img src="/assets/images/icons/log-out.png" class="icon" alt="Logout"></a>
                </div>
            <?php else: ?>
                <a href="/login" class="auth_btn">Sign In</a>
                <a href="/register" class="auth_btn primary">Create Account</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.account_btn');
    const sidebar = document.querySelector('.account_menu');
    const overlay = document.querySelector('.sidebar-overlay');

    menuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeSidebar();
    });

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    }

    // Close sidebar when clicking outside of it
    document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
            closeSidebar();
        }
    });
});
</script>


 