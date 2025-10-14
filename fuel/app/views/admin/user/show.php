<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>User Details</h4>
                    <div>
                        <a href="<?php echo Uri::create('admin/user/edit/' . $user['id']); ?>" class="btn btn-success">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        <a href="<?php echo Uri::create('admin/user'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>ID:</strong></td>
                                    <td><?= e($user['id']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Username:</strong></td>
                                    <td><?= e($user['username']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td><?= e($user['name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?= e($user['email']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td><?= e($user['phone']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>
                                        <span class="badge <?= $user['gender'] == 0 ? 'bg-info' : 'bg-warning' ?>">
                                            <?= $user['gender'] == 0 ? 'Nam' : 'Nữ' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Group:</strong></td>
                                    <td>
                                        <span class="badge <?= $user['group'] == 100 ? 'bg-danger' : 'bg-primary' ?>">
                                            <?= e($user['group_name']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>
                                        <?php 
                                        $created_at = $user['created_at'] ?? 0;
                                        echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>
                                        <?php 
                                        $last_login = $user['last_login'] ?? 0;
                                        echo $last_login > 0 ? date('Y-m-d H:i:s', $last_login) : 'Never';
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <?php if (!empty($user['avt'])): ?>
                                    <img src="<?= Uri::base(false) . $user['avt'] ?>" 
                                         alt="User Avatar" 
                                         class="img-thumbnail mb-3" 
                                         style="max-width: 150px; max-height: 150px;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                         style="width: 150px; height: 150px; margin: 0 auto;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <h5><?= e($user['name']) ?></h5>
                                <p class="text-muted"><?= e($user['username']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Actions</h5>
                        <div class="btn-group">
                            <a href="<?php echo Uri::create('admin/user/edit/' . $user['id']); ?>" 
                               class="btn btn-success">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>
                            <form action="<?php echo Uri::create('admin/user/delete/' . $user['id']); ?>" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                <input type="hidden" name="<?= \Config::get('security.csrf_token_key') ?>" value="<?= \Security::fetch_token() ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
