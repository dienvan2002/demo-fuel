<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>User Management</h4>
                    <div>
                        <a href="<?php

use Fuel\Core\Pagination;

 echo Uri::create('admin/user/create'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="<?php echo Uri::create('admin/user/search'); ?>" method="GET" class="d-flex">
                                <input type="text" 
                                       name="keyword" 
                                       class="form-control me-2" 
                                       placeholder="Search by username, email, or name..."
                                       value="<?php echo isset($keyword) ? e($keyword) : '' ?>">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>Search
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Group</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users) && count($users) > 0): ?>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= e($user['id']) ?></td>
                                        <td><?= e($user['username']) ?></td>
                                        <td><?= e($user['name']) ?></td>
                                        <td><?= e($user['email']) ?></td>
                                        <td>
                                            <span class="badge <?php echo $user['group'] == 100 ? 'bg-danger' : 'bg-primary' ?>">
                                                <?= e($user['group_name']) ?>
                                            </span>
                                        </td>
                                        <td><?= e($user['phone']) ?></td>
                                        <td>
                                            <span class="badge <?php echo $user['gender'] == 0 ? 'bg-info' : 'bg-warning' ?>">
                                                <?= $user['gender'] == 0 ? 'Nam' : 'Nữ' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $created_at = $user['created_at'] ?? 0;
                                            echo $created_at > 0 ? date('Y-m-d H:i:s', $created_at) : 'N/A';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex">
                                                <a href="<?php echo Uri::create('admin/user/edit/' . $user['id']); ?>" 
                                                   class="btn btn-success">Edit</a>
                                                <a href="<?php echo Uri::create('admin/user/show/' . $user['id']); ?>" 
                                                   class="btn btn-info">Show</a>

                                                <form action="<?php echo Uri::create('admin/user/delete/' . $user['id']); ?>" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    <!-- <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key') ?>" value="<?php echo \Security::fetch_token() ?>"> -->
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($pagination) && !empty($pagination)): ?>
                        <?php echo Pagination::instance('user_pagination')->render() ; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
