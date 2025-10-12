<?php

namespace Fuel\Tasks;

/**
 * Oil task để fix admin password
 * Chạy: php oil r fixadmin
 */
class Fixadmin
{
	/**
	 * Reset password admin
	 */
	public function run()
	{
		try {
			echo "Đang reset password admin...\n";
			
			// Xóa admin cũ
			\DB::delete('users')
				->where('username', 'admin')
				->execute();
			echo "✅ Đã xóa admin cũ\n";
			
			// Tạo admin mới với Auth::create_user()
			$user_id = \Auth::create_user(
				'admin',           // username
				'admin123',        // password
				'admin@demo.com',  // email
				100                // group (100 = Admin)
			);
			
			echo "✅ Tạo admin mới thành công! ID: $user_id\n";
			
			// Cập nhật thông tin bổ sung
			$profile_data = array(
				'name' => 'Administrator',
				'phone' => '0123456789',
				'gender' => 0,
				'avt' => '',
			);

			\DB::update('users')
				->set(array('profile_fields' => serialize($profile_data)))
				->where('id', $user_id)
				->execute();
			
			echo "✅ Cập nhật profile thành công!\n";
			
			// Kiểm tra password hash
			$user = \DB::select('id', 'username', 'password', 'group')
				->from('users')
				->where('id', $user_id)
				->execute()
				->as_array();
			
			if (!empty($user)) {
				echo "\n📋 Thông tin admin mới:\n";
				echo "ID: " . $user[0]['id'] . "\n";
				echo "Username: " . $user[0]['username'] . "\n";
				echo "Group: " . $user[0]['group'] . "\n";
				echo "Password hash: " . substr($user[0]['password'], 0, 50) . "...\n";
				
				// Test login
				echo "\n🔍 Test login...\n";
				if (\Auth::instance()->login('admin', 'admin123')) {
					echo "✅ Login TEST THÀNH CÔNG!\n";
					\Auth::logout();
				} else {
					echo "❌ Login TEST THẤT BẠI!\n";
					echo "⚠️  Có thể do config salt hoặc iterations không đúng\n";
				}
			}
			
			echo "\n📋 Thông tin đăng nhập:\n";
			echo "Username: admin\n";
			echo "Password: admin123\n";
			echo "\n🌐 Truy cập: http://localhost:8888/auth/login\n";
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
			echo "Trace: " . $e->getTraceAsString() . "\n";
		}
	}
}
