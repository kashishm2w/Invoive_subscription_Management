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
                    $tax = (float)$product['tax_percent'];
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
                            <p class="product-price">&#8377;<?= number_format($total, 2) ?></p>

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
            <div class="pagination">
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>" <?= $i === $pagination['current_page'] ? 'class="active"' : '' ?>>
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
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