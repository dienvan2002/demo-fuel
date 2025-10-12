<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Demo Fuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Flash Messages -->
        <?php if (Session::get_flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo Session::get_flash('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (Session::get_flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo Session::get_flash('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="fas fa-user-circle me-2"></i>
                            Trang chủ User
                        </h2>
                        <p class="text-muted">Chào mừng bạn đến với Demo Fuel!</p>
                        
                        <?php if (isset($current_user)): ?>
                        <div class="mt-3">
                            <p><strong>Username:</strong> <?php echo $current_user[1]; ?></p>
                            <p><strong>Email:</strong> <?php echo $current_user[4]; ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($current_profile)): ?>
                        <div class="mt-3">
                            <p><strong>Họ tên:</strong> <?php echo isset($current_profile['name']) ? $current_profile['name'] : 'N/A'; ?></p>
                            <p><strong>Điện thoại:</strong> <?php echo isset($current_profile['phone']) ? $current_profile['phone'] : 'N/A'; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Sản phẩm</h5>
                        <p class="card-text">Xem danh sách sản phẩm</p>
                        <a href="<?php echo Uri::create('user/products'); ?>" class="btn btn-primary">Xem ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Giỏ hàng</h5>
                        <p class="card-text">Quản lý giỏ hàng của bạn</p>
                        <a href="<?php echo Uri::create('user/cart'); ?>" class="btn btn-success">Xem giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-cog fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Tài khoản</h5>
                        <p class="card-text">Quản lý thông tin cá nhân</p>
                        <a href="<?php echo Uri::create('user/profile'); ?>" class="btn btn-info">Cài đặt</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?php echo Uri::create('auth/logout'); ?>" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
