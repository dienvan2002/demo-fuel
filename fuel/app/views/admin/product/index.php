<div class="container">
    <div class="row">
        <div class="col-md-12">

            <!-- Hiển thị thông báo từ session -->
            <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success">
                <?= Session::get_flash('success') ?>
            </div>
            <?php endif; ?>

            <?php if (Session::get_flash('error')): ?>
            <div class="alert alert-danger">
                <?= Session::get_flash('error') ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h4>Products List
                        <a href="<?php echo Uri::create('admin/product/create'); ?>" class="btn btn-primary float-end">Add Product</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products) && count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= e($product['id'] ?? $product->id) ?></td>
                                    <td><?= e($product['name'] ?? $product->name) ?></td>
                                    <td>
                                        <?php 
                                        $category = Service_Category::getById($product['idCategory'] ?? $product->idCategory);
                                        echo $category ? e($category['name']) : 'N/A';
                                        ?>
                                    </td>
                                    <td><?= number_format($product['price'] ?? $product->price) ?> VND</td>
                                    <td>
                                        <?php 
                                        $visible = $product['visible'] ?? $product->visible ?? 0;
                                        if ($visible == 1): 
                                        ?>
                                            <span class="badge bg-success">Visible</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Hidden</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $created_at = $product['created_at'] ?? $product->created_at ?? 0;
                                        echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex">
                                            <a href="<?php echo Uri::create('admin/product/edit/' . ($product['id'] ?? $product->id)); ?>" 
                                               class="btn btn-success">Edit</a>
                                            <a href="<?php echo Uri::create('admin/product/show/' . ($product['id'] ?? $product->id)); ?>" 
                                               class="btn btn-info">Show</a>

                                            <form action="<?php echo Uri::create('admin/product/delete/' . ($product['id'] ?? $product->id)); ?>" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure?')">
                                                <input type="hidden" name="<?= \Config::get('security.csrf_token_key') ?>" value="<?= \Security::fetch_token() ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if (isset($pagination) && !empty($pagination)): ?>
                        <?php echo $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
