<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Category Details
                        <a href="<?php echo Uri::create('admin/category'); ?>" class="btn btn-secondary float-end">Back to List</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="20%">ID</th>
                                <td><?= e($category->id) ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?= e($category->name) ?></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>
                                    <?php 
                                    $created_at = $category->created_at ?? 0;
                                    echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>
                                    <?php 
                                    $updated_at = $category->updated_at ?? 0;
                                    echo $updated_at > 0 ? date('Y-m-d H:i:s', $updated_at) : 'N/A';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Products Count</th>
                                <td>
                                    <?php 
                                    $product_count = count($category->products);
                                    echo $product_count . ' products';
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <a href="<?php echo Uri::create('admin/category/edit/' . $category->id); ?>" class="btn btn-success">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>
                        
                        <form action="<?php echo Uri::create('admin/category/delete/' . $category->id); ?>" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                            <input type="hidden" name="<?= \Config::get('security.csrf_token_key') ?>" value="<?= \Security::fetch_token() ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
