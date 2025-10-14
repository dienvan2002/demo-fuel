<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Create Product
                        <a href="<?php echo Uri::create('admin/product'); ?>" class="btn btn-danger float-end">Back</a>
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

                    <form action="<?php echo Uri::create('admin/product/create'); ?>" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Protection (FuelPHP tự động xử lý) -->

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   placeholder="Enter product name"
                                   value="<?php echo Input::post('name', ''); ?>"
                                   required />
                            <?php if (isset($error_messages['name'])): ?>
                            <span class="text-danger"><?= $error_messages['name'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="idCategory" class="form-label">Category</label>
                            <select name="idCategory" id="idCategory" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $id => $name): ?>
                                <option value="<?= $id ?>" <?= (Input::post('idCategory') == $id) ? 'selected' : '' ?>>
                                    <?= e($name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($error_messages['idCategory'])): ?>
                            <span class="text-danger"><?= $error_messages['idCategory'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price (VND)</label>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   class="form-control" 
                                   placeholder="Enter price"
                                   value="<?php echo Input::post('price', ''); ?>"
                                   min="0"
                                   step="1000"
                                   required />
                            <?php if (isset($error_messages['price'])): ?>
                            <span class="text-danger"><?= $error_messages['price'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Enter product description"><?php echo Input::post('description', ''); ?></textarea>
                            <?php if (isset($error_messages['description'])): ?>
                            <span class="text-danger"><?= $error_messages['description'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="img" class="form-label">Product Image</label>
                            <input type="file" 
                                   name="img" 
                                   id="img" 
                                   class="form-control" 
                                   accept="image/*"
                                   required />
                            <small class="form-text text-muted">JPG, PNG, GIF (Max 2MB)</small>
                            <?php if (isset($error_messages['img'])): ?>
                            <span class="text-danger"><?= $error_messages['img'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <br/>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" 
                                       name="visible" 
                                       id="visible" 
                                       class="form-check-input" 
                                       value="1"
                                       <?php echo (Input::post('visible', '1') == '1') ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="visible">
                                    Visible (Show product to customers)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Product
                            </button>
                            <a href="<?php echo Uri::create('admin/product'); ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>