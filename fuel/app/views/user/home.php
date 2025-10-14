<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content">
        <div class="welcome-text">
            <h1>Chào mừng, <?= e($current_user['name']) ?>!</h1>
            <p>Chào mừng bạn đến với Shop Demo. Hãy khám phá các sản phẩm tuyệt vời của chúng tôi.</p>
        </div>
        <div class="welcome-avatar">
            <?php if (!empty($profile['avt'])): ?>
                <img src="<?= Uri::base(false) . $profile['avt'] ?>" alt="Avatar">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['total_orders'] ?></h3>
                <p>Đơn hàng</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
                <h3><?= number_format($stats['total_spent']) ?> VNĐ</h3>
                <p>Tổng chi tiêu</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['cart_items'] ?></h3>
                <p>Giỏ hàng</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="actions-section">
    <h2>Thao tác nhanh</h2>
    <div class="actions-grid">
        <a href="<?= Uri::create('products') ?>" class="action-card">
            <i class="fas fa-box"></i>
            <h3>Xem sản phẩm</h3>
            <p>Khám phá các sản phẩm mới</p>
        </a>
        
        <a href="<?= Uri::create('user/cart') ?>" class="action-card">
            <i class="fas fa-shopping-cart"></i>
            <h3>Giỏ hàng</h3>
            <p>Xem và quản lý giỏ hàng</p>
        </a>
        
        <a href="<?= Uri::create('categories') ?>" class="action-card">
            <i class="fas fa-tags"></i>
            <h3>Danh mục</h3>
            <p>Duyệt theo danh mục</p>
        </a>
        
        <a href="<?= Uri::create('auth/logout') ?>" class="action-card logout">
            <i class="fas fa-sign-out-alt"></i>
            <h3>Đăng xuất</h3>
            <p>Thoát khỏi tài khoản</p>
        </a>
    </div>
</div>

<!-- User Info -->
<div class="user-info-section">
    <h2>Thông tin cá nhân</h2>
    <div class="user-info-grid">
        <div class="info-item">
            <label>Tên:</label>
            <span><?= e($current_user['name']) ?></span>
        </div>
        <div class="info-item">
            <label>Username:</label>
            <span><?= e($current_user['username']) ?></span>
        </div>
        <div class="info-item">
            <label>Email:</label>
            <span><?= e($current_user['email']) ?></span>
        </div>
        <div class="info-item">
            <label>Điện thoại:</label>
            <span><?= e($profile['phone'] ?? 'Chưa cập nhật') ?></span>
        </div>
        <div class="info-item">
            <label>Giới tính:</label>
            <span class="gender-badge <?= ($profile['gender'] ?? 0) == 0 ? 'male' : 'female' ?>">
                <?= ($profile['gender'] ?? 0) == 0 ? 'Nam' : 'Nữ' ?>
            </span>
        </div>
        <div class="info-item">
            <label>Tham gia:</label>
            <span>
                <?php 
                $created_at = $current_user['created_at'] ?? 0;
                echo $created_at > 0 ? date('d/m/Y', $created_at) : 'N/A';
                ?>
            </span>
        </div>
    </div>
</div>
