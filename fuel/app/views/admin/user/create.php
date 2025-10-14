<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Create New User
                        <a href="<?php echo Uri::create('admin/user'); ?>" class="btn btn-danger float-end">Back</a>
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

                    <form action="<?php echo Uri::create('admin/user/create'); ?>" method="POST">
                        <!-- CSRF Protection (FuelPHP tự động xử lý) -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" 
                                           name="username" 
                                           id="username" 
                                           class="form-control" 
                                           placeholder="Enter username"
                                           value="<?php echo Input::post('username'); ?>"
                                           required />
                                    <?php if (isset($error_messages['username'])): ?>
                                    <span class="text-danger"><?= $error_messages['username'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control" 
                                           placeholder="Enter email"
                                           value="<?php echo Input::post('email'); ?>"
                                           required />
                                    <?php if (isset($error_messages['email'])): ?>
                                    <span class="text-danger"><?= $error_messages['email'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control" 
                                           placeholder="Enter full name"
                                           value="<?php echo Input::post('name'); ?>"
                                           required />
                                    <?php if (isset($error_messages['name'])): ?>
                                    <span class="text-danger"><?= $error_messages['name'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" 
                                           name="phone" 
                                           id="phone" 
                                           class="form-control" 
                                           placeholder="Enter phone number"
                                           value="<?php echo Input::post('phone'); ?>" />
                                    <?php if (isset($error_messages['phone'])): ?>
                                    <span class="text-danger"><?= $error_messages['phone'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control" 
                                           placeholder="Enter password"
                                           required />
                                    <small class="form-text text-muted">Minimum 6 characters</small>
                                    <?php if (isset($error_messages['password'])): ?>
                                    <span class="text-danger"><?= $error_messages['password'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="group" class="form-label">Group</label>
                                    <select name="group" id="group" class="form-control" required>
                                        <option value="">Select Group</option>
                                        <?php foreach ($group_options as $value => $label): ?>
                                        <option value="<?= $value ?>" <?= (Input::post('group') == $value) ? 'selected' : '' ?>>
                                            <?= e($label) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($error_messages['group'])): ?>
                                    <span class="text-danger"><?= $error_messages['group'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <br/>
                            <div class="form-check form-check-inline">
                                <input type="radio" 
                                       name="gender" 
                                       id="gender_male" 
                                       class="form-check-input" 
                                       value="0"
                                       <?php echo (Input::post('gender', '0') == '0') ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="gender_male">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" 
                                       name="gender" 
                                       id="gender_female" 
                                       class="form-check-input" 
                                       value="1"
                                       <?php echo (Input::post('gender') == '1') ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="gender_female">Nữ</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                            <a href="<?php echo Uri::create('admin/user'); ?>" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
