<?php

/**
 * Script tạo admin đầu tiên
 * Chạy: php create_admin.php
 */

// Định nghĩa constants trước khi load FuelPHP
define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);
define('APPPATH', realpath(__DIR__.'/fuel/app/').DIRECTORY_SEPARATOR);
define('PKGPATH', realpath(__DIR__.'/fuel/packages/').DIRECTORY_SEPARATOR);
define('COREPATH', realpath(__DIR__.'/fuel/core/').DIRECTORY_SEPARATOR);

// Bootstrap the framework - THIS LINE NEEDS TO BE FIRST!
require COREPATH.'bootstrap.php';

// Add framework overload classes here
\Autoloader::add_classes(array(
    // Example: 'View' => APPPATH.'classes/myview.php',
));

// Register the autoloader
\Autoloader::register();

// Initialize the framework with the config file.
\Fuel::init('config.php');

try {
    // Tạo user admin đầu tiên
    echo "Đang tạo user admin...\n";
    
    $user_id = Auth::create_user(
        'admin',           // username
        'admin123',        // password
        'admin@demo.com',  // email
        100                // group (100 = Admin)
    );
    
    echo "✅ Tạo user admin thành công! ID: $user_id\n";
    
    // Cập nhật thông tin bổ sung
    $user = Model_User::find($user_id);
    $user->name = 'Administrator';
    $user->phone = '0123456789';
    $user->gender = 0;
    $user->avt = '';
    $user->isAdmin = 1;
    $user->save();
    
    echo "✅ Cập nhật thông tin admin thành công!\n";
    echo "\n📋 Thông tin đăng nhập:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "Email: admin@demo.com\n";
    echo "\n🌐 Truy cập: http://localhost:8888/auth/login\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
