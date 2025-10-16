<!-- Product Detail -->
<div class="product-detail">
    <div class="product-detail-content">
        <!-- Product Image -->
        <div class="product-image-section">
            <?php if (!empty($product['img'])): ?>
                <img src="<?= Uri::base(false) . $product['img'] ?>" 
                     alt="<?= e($product['name']) ?>" 
                     class="product-main-image">
            <?php else: ?>
                <div class="product-placeholder">
                    <i class="fas fa-image fa-3x"></i>
                    <p>Không có ảnh</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="product-info-section">
            <h1 class="product-title"><?= e($product['name']) ?></h1>
            
            <div class="product-price">
                <span class="price"><?= number_format($product['price']) ?> VNĐ</span>
            </div>

            <div class="product-description">
                <h3>Mô tả sản phẩm</h3>
                <p><?= e($product['description']) ?></p>
            </div>

            <div class="product-actions">
                <button class="btn btn-primary add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                </button>
                <a href="<?= Uri::create('user/products') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="product-meta">
                <div class="meta-item">
                    <label>Danh mục:</label>
                    <span><?= e($categories[$product['idCategory']] ?? 'Không xác định') ?></span>
                </div>
                <div class="meta-item">
                    <label>Trạng thái:</label>
                    <span class="status-badge <?= $product['visible'] == 1 ? 'active' : 'inactive' ?>">
                        <?= $product['visible'] == 1 ? 'Hiển thị' : 'Ẩn' ?>
                    </span>
                </div>
                <div class="meta-item">
                    <label>Ngày tạo:</label>
                    <span>
                        <?php 
                        $created_at = $product['created_at'] ?? 0;
                        echo $created_at > 0 ? date('d/m/Y H:i', $created_at) : 'N/A';
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related_products) && count($related_products) > 0): ?>
    <div class="related-products">
        <h2>Sản phẩm liên quan</h2>
        <div class="related-grid">
            <?php foreach ($related_products as $related): ?>
            <div class="related-card">
                <div class="related-image">
                    <?php if (!empty($related['img'])): ?>
                        <img src="<?= Uri::base(false) . $related['img'] ?>" alt="<?= e($related['name']) ?>">
                    <?php else: ?>
                        <div class="placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="related-info">
                    <h4><?= e($related['name']) ?></h4>
                    <div class="related-price"><?= number_format($related['price']) ?> VNĐ</div>
                    <a href="<?= Uri::create('user/products/detail/' . $related['id']) ?>" class="btn btn-sm btn-primary">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Add to cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            // Simple alert for now
            alert('Đã thêm sản phẩm vào giỏ hàng!');
            
            // Update button
            this.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
            this.style.background = '#27ae60';
            this.disabled = true;
        });
    }
});
</script>
