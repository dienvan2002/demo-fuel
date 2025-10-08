<div class="create-page-wrapper">
      <h2 class="title">Thêm sản phẩm mới</h2>

      <?php if (isset($error_messages) && !empty($error_messages)): ?>
      <div class="alert error">
            <ul>
                  <?php foreach ($error_messages as $error): ?>
                  <li><?= $error ?></li>
                  <?php endforeach; ?>
            </ul>
      </div>
      <?php endif; ?>

      <?php if (isset($success_message) && !empty($success_message)): ?>
      <div class="alert alert-success">
            <i class="icon"></i>
            <div class="alert-content">
                  <?= $success_message ?>
            </div>
      </div>
      <?php endif; ?>

      <div class="form-container">
            <?= Form::open(['action' => 'admin/product/create', 'method' => 'post', 'enctype' => 'multipart/form-data']) ?>

            <div class="form-group">
                  <?= Form::label('Tên sản phẩm', 'name') ?><span class="required">*</span>
                  <?= Form::input('name', Input::post('name'), ['required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                  <?= Form::label('Giá sản phẩm', 'price') ?><span class="required">*</span>
                  <?= Form::input('price', Input::post('price'), ['required' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                  <?= Form::label('Mô tả', 'description') ?>
                  <?= Form::textarea('description', Input::post('description'), ['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                  <?= Form::label('Ảnh sản phẩm ', 'img') ?>
                  <?= Form::file('img', ['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                  <?= Form::label('Danh mục', 'idCategory') ?><span class="required">*</span>
                  <?= Form::select('idCategory', Input::post('idCategory'), $categories, ['class' => 'form-control']) ?>
            </div>


            <div class="form-actions">
                  <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                  <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
            </div>

            <?= Form::close() ?>
      </div>
</div>