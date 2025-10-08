<div class="search-page-wrapper">
      <h2 class="title">Tìm kiếm sản phẩm</h2>

      <?= Form::open(['action' => 'admin/product/search', 'method' => 'get']) ?>

      <div class="form-group">
            <?= Form::label('Tên sản phẩm', 'keyword') ?>
            <?= Form::input('keyword', Input::get('keyword'), ['class' => 'form-control', 'placeholder' => 'Nhập tên sản phẩm...']) ?>
      </div>

      <div class="form-group">
            <?= Form::label('Danh mục', 'idCategory') ?>
            <?= Form::select('idCategory', Input::get('idCategory'), $categories, ['class' => 'form-control']) ?>
      </div>

      <div class="form-actions">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
      </div>

      <?= Form::close() ?>

      <?php if (isset($products) && count($products) > 0): ?>
      <h3 class="result-title">Kết quả tìm kiếm:</h3>
      <ul class="product-list">
            <?php foreach ($products as $product): ?>
            <li class="product-item">
                  <strong><?= $product['name'] ?></strong> - <?= number_format($product['price']) ?> VND
                  <br>
                  <img src="/<?= $product['img'] ?>" alt="<?= $product['name'] ?>" style="max-width: 150px;">
                  <p><?= $product['description'] ?></p>
                  <div class="action-buttons">
                        <a href="<?= Uri::create('admin/product/edit/' . $product['id']) ?>"
                              class="btn btn-sm btn-warning">Sửa</a>
                        <a href="<?= Uri::create('admin/product/delete/' . $product['id']) ?>"
                              class="btn btn-sm btn-danger"
                              onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                  </div>
            </li>
            <?php endforeach; ?>
      </ul>
      <?php elseif (Input::get('keyword') || Input::get('idCategory')): ?>
      <div class="alert warning">Không tìm thấy sản phẩm nào phù hợp.</div>
      <?php endif; ?>
</div>