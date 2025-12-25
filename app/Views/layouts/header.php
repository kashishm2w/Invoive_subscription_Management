<?php

use App\Helpers\Session;
?>
<link rel="stylesheet" href="/assets/css/header_footer.css">


<header class="main-header">
    <div class="container header_content">
        <div class="header_logo">Invoice & Subscription System</div>

        <nav class="header_nav">
            <ul class="nav_list">
                <?php if (\App\Helpers\Session::get('role') === 'admin'): ?>
                    <li class="nav_item">
                        <a href="/dashboard" class="nav_link">Dashboard</a>
                    </li>
                <?php endif; ?> <li class="nav_item"><a href="my_invoices" class="nav_link">Invoices</a></li>
                <li class="nav_item"><a href="/products" class="nav_link">Products</a></li>
                <li class="nav_item"><a href="/subscriptions" class="nav_link">Subscriptions</a></li>
            </ul>
        </nav>

        <div class="header_account">
            <?php if (Session::has('user_id')): ?>
                Welcome, <strong class="top-bar_username"><?= htmlspecialchars(Session::get('name')) ?></strong>

                <button class="account_btn">
                    <img src="/assets/images/icons/menu.png" class="icon">
                </button>

                <div class="account_menu">
                    <?php if (Session::get('role') !== 'admin'): ?>

                        <a href="/profile" class="account_link">My Profile</a>

                        <a href="/profile" class="account_link">My Order</a>
                        <a href="/my_invoices" class="account_link">My Invoices</a>

                    <?php endif; ?>
                    <?php if (Session::get('role') === 'admin'): ?>
                        <a href="/dashboard/add-product" class="account_link">Add Product</a>
                        <a href="/dashboard/products" class="account_link">Manage Products</a>
                        <a href="/dashboard/invoices" class="account_link">Track Invoices</a>
                    <?php endif; ?>
                                            <a href="/settings" class="account_link">Settings</a>

                    <a href="/logout" class="account_link">Logout<img src="/assets/images/icons/log-out.png" class="icon"></a>
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
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
</script>
