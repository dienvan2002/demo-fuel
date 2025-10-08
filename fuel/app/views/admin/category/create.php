<div class="create-page-wrapper">
      <h2 class="title">Thêm danh mục sản phẩm</h2>

      <?php if (Session::get_flash('errors')): ?>
      <div class="alert error">
            <ul>
                  <?php foreach (Session::get_flash('errors') as $error): ?>
                  <li><?= $error ?></li>
                  <?php endforeach; ?>
            </ul>
      </div>
      <?php endif; ?>

      <?php if (Session::get_flash('success')): ?>
      <div class="alert success"><?= Session::get_flash('success') ?></div>
      <?php endif; ?>

      <div class="form-container">
            <?= Form::open(['action' => 'admin/category/create', 'method' => 'post']) ?>
            <div class="form-group">
                  <?= Form::label('Tên danh mục', 'name') ?><span class="required">*</span>
                  <?= Form::input('name', Input::post('name'), ['required' => true]) ?>
            </div>
            <div class="form-actions">
                  <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                  <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
            </div>
            <?= Form::close() ?>
      </div>
</div>