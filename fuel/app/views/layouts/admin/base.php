<!DOCTYPE html>
<html lang="vi">

<head>
      <meta charset="UTF-8">
      <title>Trang quản trị</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- CSS chung -->
      <link rel="stylesheet" href="<?= Uri::base(false) ?>assets/css/admin/base.css">

      <!-- CSS riêng từng trang -->
      <?php if (isset($custom_css)): ?>
      <link rel="stylesheet" href="<?= Uri::base(false) . $custom_css ?>">
      <?php endif; ?>
</head>

<body>
      <div class="admin-wrapper">
            <aside class="admin-sidebar">
                  <?= View::forge('partials/admin/side_menu') ?>
            </aside>

            <main class="admin-main">
                  <?= isset($main_content) ? $main_content : '' ?>
            </main>
      </div>
</body>

</html>