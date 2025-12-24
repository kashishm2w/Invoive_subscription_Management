<?php

use App\Helpers\Session;
?>
<link rel="stylesheet" href="assets/css/header_footer.css">

<div class="top-bar">
    <div class="container top-bar__content">
        <div class="top-bar__left">
            <span class="top-bar__item">(854) 269-1413</span>
            <span class="top-bar__item">info@yourcompany.com</span>
        </div>
    </div>
</div>

<header class="main-header">
    <div class="container header__content">
        <div class="header__logo">Invoice & Subscription System</div>

        <nav class="header__nav">
            <ul class="nav__list">
                <li class="nav__item"><a href="/dashboard" class="nav__link">Dashboard</a></li>
                <li class="nav__item"><a href="/invoices" class="nav__link">Invoices</a></li>
                <li class="nav__item"><a href="/clients" class="nav__link">Clients</a></li>
                <li class="nav__item"><a href="/products" class="nav__link">Products</a></li>
                <li class="nav__item"><a href="/subscriptions" class="nav__link">Subscriptions</a></li>
            </ul>
        </nav>

        <div class="header__account">
            <?php if (Session::has('user_id')): ?>
                Welcome, <strong class="top-bar__username"><?= htmlspecialchars(Session::get('name')) ?></strong>


                <button class="account__btn">ACCOUNT ▾</button>
                <div class="account__menu">
                    <a href="/profile" class="account__link">My Profile</a>
                    <a href="/settings" class="account__link">Settings</a>
                    <?php if (Session::get('role') === 'admin'): ?>
                        <a href="/dashboard/add-product" class="account__btn" style="margin-left:10px;">Add Product</a>
                        <a href="/dashboard/products" class="account__link">Manage Products</a>
                        <a href="/dashboard/invoices" class="account__link">Track Invoices</a>
                    <?php endif; ?>
                    <a href="/logout" class="account__link">Logout</a>
                </div>
            <?php else: ?>
                <a href="/login" class="account__btn">Sign In</a>
                <a href="/register" class="account__btn" style="margin-left:10px;">Create Account</a>
            <?php endif; ?>
        </div>

    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountBtn = document.querySelector('.account__btn');
    const accountMenu = document.querySelector('.account__menu');

    // Toggle dropdown on button click
    accountBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent click from closing immediately
        accountMenu.classList.toggle('active');
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function() {
        accountMenu.classList.remove('active');
    });

    // Prevent closing when clicking inside menu
    accountMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>
