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
      
      <!-- CSS cho Action buttons -->
      <style>
      .table td:last-child {
          white-space: nowrap !important;
          text-align: center !important;
      }
      
      .table td:last-child .d-flex {
          display: flex !important;
          justify-content: center !important;
          align-items: center !important;
          gap: 8px !important;
          flex-wrap: nowrap !important;
      }
      
      .table td:last-child .btn {
          margin: 0 !important;
          padding: 6px 12px !important;
          font-size: 13px !important;
          min-width: 70px !important;
          flex-shrink: 0 !important;
      }
      
      /* Responsive */
      @media (max-width: 768px) {
          .table td:last-child .d-flex {
              gap: 4px !important;
          }
          
          .table td:last-child .btn {
              padding: 4px 8px !important;
              font-size: 12px !important;
              min-width: 60px !important;
          }
      }
      
      @media (max-width: 576px) {
          .table td:last-child .d-flex {
              flex-direction: column !important;
              gap: 3px !important;
          }
          
          .table td:last-child .btn {
              width: 100% !important;
              margin-bottom: 3px !important;
          }
      }
      
      /* FuelPHP Pagination Styles */
      .pagination {
          margin-top: 20px;
          text-align: center;
      }
      
      .pagination span {
          display: inline-block;
          margin: 0 2px;
      }
      
      .pagination a {
          display: inline-block;
          padding: 8px 12px;
          text-decoration: none;
          color: #337ab7;
          background-color: #fff;
          border: 1px solid #ddd;
          border-radius: 4px;
          transition: all 0.3s ease;
          min-width: 40px;
          text-align: center;
      }
      
      .pagination a:hover {
          color: #23527c;
          background-color: #f5f5f5;
          border-color: #adadad;
      }
      
      .pagination .active a {
          color: #fff;
          background-color: #337ab7;
          border-color: #337ab7;
          font-weight: bold;
      }
      
      .pagination .previous-inactive a,
      .pagination .next-inactive a {
          color: #999;
          background-color: #f5f5f5;
          border-color: #ddd;
          cursor: not-allowed;
      }
      
      .pagination .previous-inactive a:hover,
      .pagination .next-inactive a:hover {
          color: #999;
          background-color: #f5f5f5;
          border-color: #ddd;
      }
      
      /* Đảm bảo pagination hiển thị đúng */
      .pagination {
          display: block !important;
          text-align: center !important;
          margin: 20px 0 !important;
      }
      
      .pagination span {
          display: inline-block !important;
          margin: 0 2px !important;
      }
      
      .pagination a {
          display: inline-block !important;
          padding: 8px 12px !important;
          text-decoration: none !important;
          color: #337ab7 !important;
          background-color: #fff !important;
          border: 1px solid #ddd !important;
          border-radius: 4px !important;
          transition: all 0.3s ease !important;
          min-width: 40px !important;
          text-align: center !important;
      }
      
      .pagination a:hover {
          color: #23527c !important;
          background-color: #f5f5f5 !important;
          border-color: #adadad !important;
      }
      
      .pagination .active a {
          color: #fff !important;
          background-color: #337ab7 !important;
          border-color: #337ab7 !important;
          font-weight: bold !important;
      }
      
      .pagination .previous-inactive a,
      .pagination .next-inactive a {
          color: #999 !important;
          background-color: #f5f5f5 !important;
          border-color: #ddd !important;
          cursor: not-allowed !important;
      }
      </style>
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