<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' - ' : '' ?>Shop</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/user/base.css" rel="stylesheet">

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Shop </span>
                </div>

                <nav class="nav">
                    <a href="<?= Uri::create('user/home') ?>" class="nav-link">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                    <a href="<?= Uri::create('products') ?>" class="nav-link active">
                        <i class="fas fa-box"></i> Sản phẩm
                    </a>
                    <a href="<?= Uri::create('user/cart') ?>" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    </a>
                </nav>

                <div class="user-menu">
                    <div class="user-info">
                        <i class="fas fa-user"></i>
                        <span><?= isset($current_user['name']) ? e($current_user['name']) : 'User' ?></span>
                    </div>
                    <a href="<?= Uri::create('auth/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <div class="content-wrapper">
                <!-- Sidebar -->
                <div class="sidebar">
                    <div class="sidebar-section">
                        <h3>Tìm kiếm sản phẩm</h3>
                        <div class="w-80">
                            <form method="GET" action="<?= Uri::create('products') ?>" class="search-form">
                                <input type="text" name="search" placeholder="Tên sản phẩm..."
                                    value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <h3>Danh mục</h3>
                        <ul class="category-list ">
                            <li><a href="<?= Uri::create('/user/products') ?>" class="category-link">Tất cả</a></li>
                            <?php if (isset($categories)): ?>
                                <?php foreach ($categories as $id => $name): ?>
                                    <li>
                                        <a href="<?= Uri::create('/user/products?category=' . $id) ?>"
                                            class="category-link <?= (isset($current_category) && $current_category == $id) ? 'active' : '' ?>">
                                            <?= e($name) ?>
                                        </a>
                                    </li>

                                    
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content">
                    <!-- Flash Messages -->
                    <?php if (Session::get_flash('success')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= Session::get_flash('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (Session::get_flash('error')): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= Session::get_flash('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <?= $main_content ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Shop Demo. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>