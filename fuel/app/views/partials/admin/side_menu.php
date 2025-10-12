<?php

use Fuel\Core\Uri;

// Lấy thông tin route hiện tại để highlight menu active
$current_controller = Uri::segment(2);
$current_action = Uri::segment(3);
?>
<div class="admin-menu">
      <div class="menu-block">
            <h3 class="title">Admin Dashboard</h3>
            <ul>
                  <li>
                        <a class="link <?= ($current_controller == 'product' && $current_action == 'search') ? 'active' : '' ?>"
                              href="<?php echo Uri::create('admin/product/search'); ?>">
                              Tìm kiếm sản phẩm
                        </a>
                  </li>
                  <li>
                        <a class="link <?= ($current_controller == 'product' && $current_action == 'create') ? 'active' : '' ?>"
                              href="<?php echo Uri::create('admin/product/create'); ?>">
                              Thêm sản phẩm
                        </a>
                  </li>
                  <li>
                        <a class="link <?= ($current_controller == 'category' && $current_action == 'create') ? 'active' : '' ?>"
                              href="<?php echo Uri::create('admin/category/create'); ?>">
                              Thêm danh mục sản phẩm
                        </a>
                  </li>
                  <li>
                        <a class="link <?= ($current_controller == 'category' && $current_action == 'user') ? 'active' : '' ?>"
                              href="<?php echo Uri::create('admin/category/user'); ?>">
                              Quản lý người dùng
                        </a>
                  </li>
            </ul>
      </div>
      
      <!-- Logout Button -->
      <div class="logout-section">
            <a href="<?php echo Uri::create('auth/logout'); ?>" class="btn-logout">
                  <i class="fas fa-sign-out-alt"></i>
                  Đăng xuất
            </a>
      </div>
</div>

<style>
.logout-section {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #e9ecef;
}

.btn-logout {
      display: block;
      width: 100%;
      background: #dc3545;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
      text-decoration: none;
      text-align: center;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
}

.btn-logout:hover {
      background: #c82333;
      color: white;
      text-decoration: none;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-logout i {
      margin-right: 8px;
}
</style>