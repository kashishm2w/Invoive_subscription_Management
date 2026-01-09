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
                <span class="welcome-text">Welcome, <strong class="top-bar_username"><?= htmlspecialchars(Session::get('name')) ?></strong></span>
                
                <!-- Modern Hamburger Menu Button -->
                <button class="account_btn" aria-label="Menu">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <div class="account_menu">
                    <div class="menu-header">
                        <span class="menu-user"><?= htmlspecialchars(Session::get('name')) ?></span>
                        <span class="menu-role"><?= ucfirst(Session::get('role')) ?></span>
                    </div>
                    
                    <div class="menu-links">
                        <?php if (Session::get('role') !== 'admin'): ?>
                            <a href="/my_invoices" class="account_link">
                                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                My Invoices
                            </a>
                        <?php endif; ?>

                        <?php if (Session::get('role') === 'admin'): ?>
                            <a href="/add-product" class="account_link">
                                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                Add Product
                            </a>
                            <a href="/track_invoices" class="account_link">
                                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                Track Invoices
                            </a>
                            <a href="/track_subscriptions" class="account_link">
                                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                Track Subscriptions
                            </a>
                        <?php endif; ?>

                        <a href="/settings" class="account_link">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            Settings
                        </a>
                    </div>
                    
                    <div class="menu-footer">
                        <a href="/logout" class="logout-btn">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            Logout
                        </a>
                    </div>
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