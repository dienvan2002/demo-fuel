<?php

namespace Fuel\Tasks;

/**
 * Oil task để tạo admin user
 * Chạy: php oil r createadmin
 */
class Createadmin
{
	/**
	 * Tạo admin user đầu tiên
	 */
	public function run()
	{
		try {
			echo "Đang tạo user admin...\n";
			
			// Kiểm tra xem đã có admin chưa
			$existing_admin = \DB::select('id', 'username', 'email')
				->from('users')
				->where('group', 100)
				->execute()
				->as_array();
			
			if (count($existing_admin) > 0) {
				echo "⚠️  Admin đã tồn tại:\n";
				foreach ($existing_admin as $admin) {
					echo "   ID: " . $admin['id'] . ", Username: " . $admin['username'] . ", Email: " . $admin['email'] . "\n";
				}
				echo "\nNếu muốn tạo lại, hãy xóa admin cũ trước:\n";
				echo "php oil r createadmin:delete " . $existing_admin[0]['id'] . "\n";
				return;
			}
			
			// Tạo user admin đầu tiên
			$user_id = \Auth::create_user(
				'admin',           // username
				'admin123',        // password
				'admin@demo.com',  // email
				100                // group (100 = Admin)
			);
			
			echo "✅ Tạo user admin thành công! ID: $user_id\n";
			
			// Cập nhật thông tin bổ sung vào profile_fields
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
			
			echo "✅ Cập nhật thông tin admin thành công!\n";
			echo "\n📋 Thông tin đăng nhập:\n";
			echo "Username: admin\n";
			echo "Password: admin123\n";
			echo "Email: admin@demo.com\n";
			echo "\n🌐 Truy cập: http://localhost:8888/auth/login\n";
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}
	
	/**
	 * Tạo user thường
	 */
	public function user($username = 'user', $password = 'user123')
	{
		try {
			echo "Đang tạo user thường: $username...\n";
			
			// Tạo user thường
			$user_id = \Auth::create_user(
				$username,         // username
				$password,         // password
				$username . '@demo.com',  // email
				1                  // group (1 = User)
			);
			
			echo "✅ Tạo user thành công! ID: $user_id\n";
			
			// Cập nhật thông tin bổ sung vào profile_fields
			$profile_data = array(
				'name' => ucfirst($username),
				'phone' => '0987654321',
				'gender' => 1,
				'avt' => '',
			);

			\DB::update('users')
				->set(array('profile_fields' => serialize($profile_data)))
				->where('id', $user_id)
				->execute();
			
			echo "✅ Cập nhật thông tin user thành công!\n";
			echo "\n📋 Thông tin đăng nhập:\n";
			echo "Username: $username\n";
			echo "Password: $password\n";
			echo "Email: $username@demo.com\n";
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}
	
	/**
	 * Xóa user theo ID
	 * php oil r createadmin:delete 1
	 */
	public function delete($user_id = null)
	{
		if (!$user_id) {
			echo "❌ Vui lòng cung cấp user ID\n";
			echo "Ví dụ: php oil r createadmin:delete 1\n";
			return;
		}

		try {
			echo "Đang xóa user ID: $user_id...\n";
			
			$affected = \DB::delete('users')
				->where('id', $user_id)
				->execute();
			
			if ($affected > 0) {
				echo "✅ Đã xóa user thành công!\n";
			} else {
				echo "⚠️  Không tìm thấy user với ID: $user_id\n";
			}
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}

	/**
	 * Liệt kê tất cả users
	 */
	public function list()
	{
		try {
			echo "📋 Danh sách users:\n\n";
			
			$users = \DB::select('id', 'username', 'group', 'profile_fields')
				->from('users')
				->execute();
			
			if (empty($users)) {
				echo "Không có user nào.\n";
				return;
			}
			
			printf("%-5s %-15s %-20s %-10s\n", 
				'ID', 'Username', 'Name', 'Group');
			echo str_repeat('-', 60) . "\n";
			
			foreach ($users as $user) {
				$group_name = $user['group'] == 100 ? 'Admin' : 'User';
				$profile = $user['profile_fields'] ? unserialize($user['profile_fields']) : array();
				$name = isset($profile['name']) ? $profile['name'] : 'N/A';
				
				printf("%-5d %-15s %-20s %-10s\n", 
					$user['id'], 
					$user['username'], 
					$name, 
					$group_name
				);
			}
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}
}
