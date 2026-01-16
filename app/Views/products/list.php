<?php 
use \App\Helpers\Session;
use \App\Models\Company;

// Get global tax rate from company settings
$companyModel = new Company();
$companyTaxRate = $companyModel->getGlobalTaxRate();

require APP_ROOT . '/app/Views/layouts/header.php';
 ?>
<link rel="stylesheet" href="/assets/css/products.css">

<main class="main-content">
    <div class="dashboard-header">
        <h2>Product Listing</h2>

        <div class="product-search">
            <input type="text" id="product_search" placeholder="Search by product name.." autocomplete="off">
        </div>

        <?php if (Session::get('role') === 'admin'): ?>
            <button type="button" class="btn-add-product" onclick="openAddProductModal()">+ Add Product</button>
        <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
        <table class="product-table" border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Product Name</th>
                    <?php if (Session::get('role') === 'admin'): ?>
                        <th>Price (&#36;)</th>
                        <th>Tax %</th>
                    <?php else: ?>
                        <th>Total Price (&#36;)</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['poster']) && file_exists(APP_ROOT . '/public/uploads/' . $product['poster'])): ?>
                                <img src="/uploads/<?= htmlspecialchars($product['poster']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-poster" style="width:60px; height:auto;">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['name']) ?></td>

                        <?php if (Session::get('role') === 'admin'): ?>
                            <td><?= number_format($product['price'], 2) ?></td>
                            <td>
                                <?php if (!empty($product['is_tax_free'])): ?>
                                    <span class="badge tax-free">Tax Free</span>
                                <?php else: ?>
                                    <?= $companyTaxRate ?>%
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <?php
                            $price = (float)$product['price'];
                            // Get global tax rate from company
                            $companyModel = new Company();
                            $globalTaxRate = $companyModel->getGlobalTaxRate();
                            $tax = !empty($product['is_tax_free']) ? 0 : $globalTaxRate;
                            $total = $price + ($price * $tax / 100);
                            ?>
                            <td><strong>&#36;<?= number_format($total, 2) ?></strong></td>
                        <?php endif; ?>

                        <td>
                            <button type="button" class="btn-view" onclick="openViewProductModal(<?= $product['id'] ?>)">View</button>

                            <?php if (Session::get('role') === 'admin'): ?>
                                <button type="button" onclick="openEditProductModal(<?= $product['id'] ?>)">
                                    Edit
                                </button>
                                | <button type="button" class="btn-delete" onclick="deleteProduct(<?= $product['id'] ?>)">Delete</button>
                            <?php else: ?>
                                | <button
                                    id="cart-btn-<?= $product['id'] ?>"
                                    onclick="addToCart(<?= $product['id'] ?>)"
                                    <?= in_array($product['id'], $cartProductIds) ? 'disabled' : '' ?>>
                                    <?= in_array($product['id'], $cartProductIds) ? 'Added' : 'Add to Cart' ?>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

      <div class="pagination">
<?php if ($pagination['total_pages'] > 1): ?>
<?php
    $currentPage = (int)$pagination['current_page'];
    $totalPages  = (int)$pagination['total_pages'];
?>

<!-- Previous -->
<?php if ($currentPage > 1): ?>
    <a href="?page=<?= $currentPage - 1 ?>" class="nav-btn">&laquo; Previous</a>
<?php endif; ?>

<?php
/* PAGE 1 */
if ($currentPage === 1):
?>
    <a href="?page=1" class="active">1</a>

    <?php if ($totalPages >= 2): ?>
        <a href="?page=2">2</a>
    <?php endif; ?>

    <?php if ($totalPages > 3): ?>
        <span class="ellipsis">...</span>
    <?php endif; ?>

    <?php if ($totalPages > 2): ?>
        <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
    <?php endif; ?>

<?php
/* PAGE 2 */
elseif ($currentPage === 2):
?>
    <a href="?page=1">1</a>
    <a href="?page=2" class="active">2</a>

    <?php if ($totalPages > 3): ?>
        <span class="ellipsis">...</span>
    <?php endif; ?>

    <a href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>

<?php
/* PAGE 3 */
elseif ($currentPage === 3):
?>
    <span class="ellipsis">...</span>
    <a href="?page=3" class="active">3</a>

    <?php if ($totalPages >= 4): ?>
        <a href="?page=4">4</a>
    <?php endif; ?>

    <?php if ($totalPages >= 5): ?>
        <a href="?page=5">5</a>
    <?php endif; ?>

<?php
/* PAGE ≥ 4 */
else:
?>
    <a href="?page=1">1</a>
    <span class="ellipsis">...</span>

    <a href="?page=<?= $currentPage - 1 ?>">
        <?= $currentPage - 1 ?>
    </a>

    <a href="?page=<?= $currentPage ?>" class="active">
        <?= $currentPage ?>
    </a>

    <?php if ($currentPage + 1 <= $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>">
            <?= $currentPage + 1 ?>
        </a>
    <?php endif; ?>

    <?php if ($currentPage + 1 < $totalPages): ?>
        <span class="ellipsis">...</span>
        <a href="?page=<?= $totalPages ?>">
            <?= $totalPages ?>
        </a>
    <?php endif; ?>

<?php endif; ?>

<!-- Next -->
<?php if ($currentPage < $totalPages): ?>
    <a href="?page=<?= $currentPage + 1 ?>" class="nav-btn">Next &raquo;</a>
<?php endif; ?>

<?php endif; ?>
</div>

    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

    <?php if (Session::get('role') !== 'admin'): ?>
        <div class="button-container">
            <a href="/cart" class="view-cart">View Cart</a>
        </div>
    <?php endif; ?>

    <!-- View Product Modal -->
    <div id="viewProductModal" class="modal">
        <div class="modal-content" id="viewProductContainer">
            <!-- Product details will be loaded here via AJAX -->
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content" id="addFormContainer">
            <!-- Form content will be loaded here via AJAX -->
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal">
        <div class="modal-content" id="editFormContainer">
            <!-- Form content will be loaded here via AJAX -->
        </div>
    </div>
</main>
<?php require APP_ROOT . '/app/Views/layouts/footer.php'; ?>

<script>
    window.IS_ADMIN = <?= Session::get('role') === 'admin' ? 'true' : 'false' ?>;
</script>
<script src="/assets/js/products_list.js"></script>