<!-- Page Header -->
<div class="page-header">
    <h1>Sản phẩm</h1>
    <?php if (isset($keyword) && !empty($keyword)): ?>
        <p>Kết quả tìm kiếm: "<?= e($keyword) ?>"</p>
    <?php elseif (isset($current_category) && !empty($current_category)): ?>
        <p>Danh mục: <?= e($categories[$current_category] ?? '') ?></p>
    <?php endif; ?>
    <p class="product-count">Tìm thấy <?= $total_products ?> sản phẩm</p>
</div>

<!-- Products Grid -->
<?php if (!empty($products) && count($products) > 0): ?>
<div class="product-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card">
        <!-- Product Image -->
        <div class="product-image">
            <?php if (!empty($product['img'])): ?>
                <img src="<?= Uri::base(false) . $product['img'] ?>" alt="<?= e($product['name']) ?>">
            <?php else: ?>
                <div class="placeholder">
                    <i class="fas fa-image"></i>
                </div>
            <?php endif; ?>
            
            <!-- Price Badge -->
            <div class="price-badge">
                <?= number_format($product['price']) ?> VNĐ
            </div>
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <h3 class="product-title"><?= e($product['name']) ?></h3>
            
            <p class="product-description">
                <?= e(Str::truncate($product['description'], 80)) ?>
            </p>

            <div class="product-actions">
                <a href="<?= Uri::create('products/detail/' . $product['id']) ?>" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Xem
                </a>
                <button class="btn btn-success add-to-cart" data-product-id="<?= $product['id'] ?>">
                    <i class="fas fa-cart-plus"></i> Mua
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Simple Pagination -->
<?php if ($total_products > $limit): ?>
<div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="?page=<?= $current_page - 1 ?>&search=<?= e($keyword) ?>&category=<?= e($current_category) ?>" class="btn">
            <i class="fas fa-chevron-left"></i> Trước
        </a>
    <?php endif; ?>
    
    <span class="page-info">Trang <?= $current_page ?></span>
    
    <?php if (count($products) == $limit): ?>
        <a href="?page=<?= $current_page + 1 ?>&search=<?= e($keyword) ?>&category=<?= e($current_category) ?>" class="btn">
            Sau <i class="fas fa-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php else: ?>
<!-- Empty State -->
<div class="empty-state">
    <i class="fas fa-box-open"></i>
    <h3>Không tìm thấy sản phẩm nào</h3>
    <p>
        <?php if (!empty($keyword) || !empty($current_category)): ?>
            Hãy thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác.
        <?php else: ?>
            Hiện tại chưa có sản phẩm nào được bán.
        <?php endif; ?>
    </p>
    <a href="<?= Uri::create('products') ?>" class="btn btn-primary">
        <i class="fas fa-refresh"></i> Xem tất cả sản phẩm
    </a>
</div>
<?php endif; ?>

<script>
// Simple add to cart
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            // Simple alert for now
            alert('Đã thêm sản phẩm vào giỏ hàng!');
            
            // Update button
            this.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
            this.style.background = '#27ae60';
            this.disabled = true;
        });
    });
});
</script>
