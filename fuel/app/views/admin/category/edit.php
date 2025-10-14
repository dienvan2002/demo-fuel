<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Category
                        <a href="<?php echo Uri::create('admin/category'); ?>" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Hiển thị thông báo lỗi -->
                    <?php if (isset($error_messages) && !empty($error_messages)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($error_messages as $error): ?>
                            <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Hiển thị thông báo thành công -->
                    <?php if (isset($success_message) && !empty($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $success_message ?>
                    </div>
                    <?php endif; ?>

                    <form action="<?php echo Uri::create('admin/category/edit/' . $category['id']); ?>" method="POST">
                        <!-- CSRF Protection (FuelPHP tự động xử lý) -->

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-2"></i>Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   placeholder="Enter category name"
                                   value="<?php echo Input::post('name', $category['name']); ?>"
                                   required />
                            <?php if (isset($error_messages['name'])): ?>
                            <span class="text-danger"><?= $error_messages['name'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update
                            </button>
                            <a href="<?php echo Uri::create('admin/category'); ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
