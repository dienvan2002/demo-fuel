<div class="container">
    <div class="row">
        <div class="col-md-12">

            <!-- Hiển thị thông báo từ session -->
            <?php if (Session::get_flash('success')): ?>
            <div class="alert alert-success">
                <?= Session::get_flash('success') ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h4>Categories List
                        <a href="<?php echo Uri::create('admin/category/create'); ?>" class="btn btn-primary float-end">Add Category</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <!-- <th>Created At</th>
                                <th>Updated At</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories) && count($categories) > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= e($category['id'] ?? $category->id) ?></td>
                                    <td><?= e($category['name'] ?? $category->name) ?></td>
                                    <!-- <td>
                                        <?php 
                                        $created_at = $category['created_at'] ?? $category->created_at ?? 0;
                                        echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $updated_at = $category['updated_at'] ?? $category->updated_at ?? 0;
                                        echo $updated_at > 0 ? date('Y-m-d H:i:s', $updated_at) : 'N/A';
                                        ?>
                                    </td> -->
                                    <td>
                                        <a href="<?php echo Uri::create('admin/category/edit/' . ($category['id'] ?? $category->id)); ?>" 
                                           class="btn btn-success">Edit</a>
                                        <a href="<?php echo Uri::create('admin/category/show/' . ($category['id'] ?? $category->id)); ?>" 
                                           class="btn btn-info">Show</a>

                                        <form action="<?php echo Uri::create('admin/category/delete/' . ($category['id'] ?? $category->id)); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure?')">
                                            <input type="hidden" name="<?= \Config::get('security.csrf_token_key') ?>" value="<?= \Security::fetch_token() ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No categories found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if (isset($pagination) && !empty($pagination)): ?>
                        <div class="pagination">
                            <?= $pagination ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>