<div class="create-page-wrapper">
      <h2 class="title">Thêm danh mục sản phẩm</h2>

      <!-- Hiển thị thông báo lỗi -->
      <?php if (isset($error_messages) && !empty($error_messages)): ?>
      <div class="alert alert-error">
            <i class="icon"></i>
            <div class="alert-content">
                  <ul>
                        <?php foreach ($error_messages as $error): ?>
                        <li><?= $error ?></li>
                        <?php endforeach; ?>
                  </ul>
            </div>
      </div>
      <?php endif; ?>

      <!-- Hiển thị thông báo thành công -->
      <?php if (isset($success_message) && !empty($success_message)): ?>
      <div class="alert alert-success">
            <i class="icon"></i>
            <div class="alert-content">
                  <?= $success_message ?>
            </div>
      </div>
      <?php endif; ?> <div class="form-container">
            <?= Form::open(['action' => 'admin/category/create', 'method' => 'post']) ?>
            <div class="form-group">
                  <?= Form::label('Tên danh mục', 'name') ?><span class="required">*</span>
                  <?= Form::input('name', Input::post('name'), ['required' => true]) ?>
            </div>
            <div class="form-actions">
                  <button type="submit" class="btn btn-primary"> Thêm danh mục</button>
                  <a href="<?= Uri::create('admin') ?>" class="btn btn-secondary"> Hủy</a>
            </div>
            <?= Form::close() ?>
      </div>
</div>

<script src="<?= Uri::base(false) ?>assets/js/admin/create.js"></script>