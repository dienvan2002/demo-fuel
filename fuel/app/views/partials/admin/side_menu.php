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
</div>