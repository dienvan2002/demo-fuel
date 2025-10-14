<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Product Details
                        <a href="<?php echo Uri::create('admin/product'); ?>" class="btn btn-secondary float-end">Back to List</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($product['img'])): ?>
                            <img src="<?= Uri::base(false) . $product['img'] ?>" alt="Product image" class="img-fluid rounded" style="max-height: 400px;">
                            <?php else: ?>
                            <div class="bg-light p-5 text-center rounded">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="mt-2 text-muted">No image available</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td><?= e($product['id']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td><?= e($product['name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>
                                            <?php 
                                            $category = Service_Category::getById($product['idCategory']);
                                            echo $category ? e($category['name']) : 'N/A';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td><?= number_format($product['price']) ?> VND</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td><?= e($product['description']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <?php if ($product['visible'] == 1): ?>
                                                <span class="badge bg-success">Visible</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Hidden</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>
                                            <?php 
                                            $created_at = $product['created_at'] ?? 0;
                                            echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>
                                            <?php 
                                            $updated_at = $product['updated_at'] ?? 0;
                                            echo $updated_at > 0 ? date('Y-m-d H:i:s', $updated_at) : 'N/A';
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-3">
                                <a href="<?php echo Uri::create('admin/product/edit/' . $product['id']); ?>" class="btn btn-success">
                                    <i class="fas fa-edit me-2"></i>Edit Product
                                </a>
                                
                                <form action="<?php echo Uri::create('admin/product/delete/' . $product['id']); ?>" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    <input type="hidden" name="<?= \Config::get('security.csrf_token_key') ?>" value="<?= \Security::fetch_token() ?>">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Delete Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
