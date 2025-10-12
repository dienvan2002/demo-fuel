<!-- Flash Messages -->
<?php if (Session::get_flash('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 20px;">
      <i class="fas fa-check-circle me-2"></i>
      <?php echo Session::get_flash('success'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (Session::get_flash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
      <i class="fas fa-exclamation-triangle me-2"></i>
      <?php echo Session::get_flash('error'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="top">
      <h2 class="title">Demo Fuel - Admin Dashboard</h2>
      <p class="description">
            Trang quản trị hệ thống Demo Fuel<br>
            Chào mừng bạn đến với trang quản trị!
      </p>
</div>