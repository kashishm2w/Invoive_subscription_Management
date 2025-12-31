<?php use App\Helpers\Session; ?>
<link rel="stylesheet" href="assets/css/header_footer.css">

<footer class="footer">

    <div class="container footer__content">
        <div class="footer__block">
            <h4>About</h4>
            <p>
                Invoice & Subscription Management System helps businesses create invoices, manage clients, collect online payments, and handle subscriptions efficiently.
            </p>
        </div>

        <div class="footer__block">
            <h4>Contact</h4>
            <p>
            (123) 456-7890<br>
            info@invoicesystem.com
            </p>
        </div>

        <div class="footer__block">
            <h4>Support</h4>
            <p>
                Monday- Friday: 09:00 AM - 06:00 PM<br>
                Saturday: 09:00 AM -01:00 PM<br>
                Sunday: Closed
            </p>
        </div>

    </div>

    <div class="footer__bottom">
        <p>
            © 2025 Invoice & Subscription Management System. All Rights Reserved.<br>
            <span>POWERED BY: Invoice & Subscription Management System</span>
        </p>
    </div>

</footer>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Global SweetAlert Handler -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (Session::has('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= addslashes(Session::get('success')) ?>',
            timer: 1500,
            showConfirmButton: false
        });
        <?php Session::remove('success'); ?>
    <?php endif; ?>

    <?php if (Session::has('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= addslashes(Session::get('error')) ?>',
            timer: 1500,
            showConfirmButton: false
        });
        <?php Session::remove('error'); ?>
    <?php endif; ?>
});
</script>

