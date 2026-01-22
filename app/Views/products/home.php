<?php
require APP_ROOT . '/app/Views/layouts/header.php';

use App\Helpers\Session;
?>
<link rel="stylesheet" href="/assets/css/home.css">
<link rel="stylesheet" href="/assets/css/products.css">

<main class="main-content">
    <section class="hero-section">
        <h1>Welcome to Our Store</h1>
        <p>Discover amazing products at great prices</p>
    </section>
    <img src="/uploads/poster.jpg" alt="poster.jpg" class="poster">
    <div class="home-container">

        <section class="products-section">
            <div class="section-header">
                <h2>Our Products</h2>
                <?php if (Session::has('user_id') && Session::get('role') !== 'admin'): ?>
                    <a href="/cart" class="cart-link">
                        View Cart
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                        $price = (float)$product['price'];
                        // Calculate tax_percent based on is_tax_free and global tax rate
                        $tax = !empty($product['is_tax_free']) ? 0 : $globalTaxRate;
                        $total = $price + ($price * $tax / 100);
                        $isInCart = in_array($product['id'], $cartProductIds);
                        ?>
                        <div class="product-card" onclick="openViewProductModal(<?= $product['id'] ?>)">
                            <div class="product-image">
                                <?php if (!empty($product['poster']) && file_exists(APP_ROOT . '/public/uploads/' . $product['poster'])): ?>
                                    <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-price">&#36;<?= number_format($total, 2) ?></p>

                                <?php if (Session::has('user_id') && Session::get('role') !== 'admin'): ?>
                                    <?php if ($isInCart): ?>
                                        <button class="btn-added" disabled onclick="event.stopPropagation();">
                                            Added
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-add-to-cart"
                                            id="home-cart-btn-<?= $product['id'] ?>"
                                            onclick="event.stopPropagation(); addToCartFromHome(<?= $product['id'] ?>)">
                                            Add to Cart
                                        </button>
                                    <?php endif; ?>
                                <?php elseif (!Session::has('user_id') || Session::get('role') !== 'admin'): ?>
                                    <?php if ($isInCart): ?>
                                        <button class="btn-added" disabled onclick="event.stopPropagation();">
                                            Added
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-add-to-cart"
                                            id="home-cart-btn-<?= $product['id'] ?>"
                                            onclick="event.stopPropagation(); addToCartFromHome(<?= $product['id'] ?>)">
                                            Add to Cart
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination" id="pagination-container">
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <?php
                        $currentPage = (int)$pagination['current_page'];
                        $totalPages  = (int)$pagination['total_pages'];
                        $range = 1; // Number of pages to show around current page
                        ?>

                        <!-- Previous Button -->
                        <?php if ($currentPage > 1): ?>
                            <a href="javascript:void(0)" onclick="loadPage(<?= $currentPage - 1 ?>)" class="nav-btn">&laquo; Previous</a>
                        <?php endif; ?>

                        <!-- First page -->
                        <a href="javascript:void(0)" onclick="loadPage(1)" <?= $currentPage === 1 ? 'class="active"' : '' ?>>1</a>

                        <!-- Ellipsis after first page -->
                        <?php if ($currentPage > $range + 2): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>

                        <!-- Pages around current page -->
                        <?php for ($i = max(2, $currentPage - $range); $i <= min($totalPages - 1, $currentPage + $range); $i++): ?>
                            <a href="javascript:void(0)" onclick="loadPage(<?= $i ?>)" <?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
                        <?php endfor; ?>

                        <!-- Ellipsis before last page -->
                        <?php if ($currentPage < $totalPages - $range - 1): ?>
                            <span class="ellipsis">...</span>
                        <?php endif; ?>

                        <!-- Last page (if more than 1 page) -->
                        <?php if ($totalPages > 1): ?>
                            <a href="javascript:void(0)" onclick="loadPage(<?= $totalPages ?>)" <?= $currentPage === $totalPages ? 'class="active"' : '' ?>><?= $totalPages ?></a>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="javascript:void(0)" onclick="loadPage(<?= $currentPage + 1 ?>)" class="nav-btn">Next &raquo;</a>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p class="no-products">No products available at the moment.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- View Product Modal -->
    <div id="viewProductModal" class="modal">
        <div class="modal-content" id="viewProductContainer">
            <!-- Product details will be loaded here via AJAX -->
        </div>
    </div>
</main>
<script src="/assets/js/products_home.js"></script>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>