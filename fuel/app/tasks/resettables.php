<?php

namespace Fuel\Tasks;

/**
 * Oil task để xóa và tạo lại bảng users
 * Chạy: php oil r resettables
 */
class Resettables
{
	/**
	 * Xóa tất cả bảng users và tạo lại
	 */
	public function run()
	{
		try {
			echo "Đang tắt foreign key checks...\n";
			
			// Tắt foreign key checks để có thể xóa bảng
			\DB::query("SET FOREIGN_KEY_CHECKS = 0")->execute();
			
			echo "Đang xóa các bảng users cũ...\n";
			
			// Xóa tất cả bảng users_* (xóa theo thứ tự ngược lại)
			$tables_to_drop = [
				'users_sessionscopes',  // Xóa bảng con trước
				'users_sessions',
				'users_scopes', 
				'users_providers',
				'users_clients',
				'users'                 // Xóa bảng cha cuối cùng
			];
			
			foreach ($tables_to_drop as $table) {
				try {
					\DBUtil::drop_table($table);
					echo "✅ Đã xóa bảng: $table\n";
				} catch (\Exception $e) {
					echo "⚠️  Bảng $table không tồn tại hoặc không thể xóa: " . $e->getMessage() . "\n";
				}
			}
			
			// Bật lại foreign key checks
			\DB::query("SET FOREIGN_KEY_CHECKS = 1")->execute();
			echo "✅ Đã bật lại foreign key checks\n";
			
			echo "\nĐang chạy migration để tạo lại bảng users...\n";
			
			// Chạy migration auth để tạo lại bảng users
			$migration = new \Fuel\Migrations\Auth_Create_Usertables();
			$migration->up();
			
			echo "✅ Đã tạo lại bảng users thành công!\n";
			echo "\nBây giờ bạn có thể chạy:\n";
			echo "php oil r createadmin\n";
			echo "hoặc truy cập: http://localhost:8888/auth/create_admin\n";
			
		} catch (\Exception $e) {
			// Đảm bảo bật lại foreign key checks nếu có lỗi
			try {
				\DB::query("SET FOREIGN_KEY_CHECKS = 1")->execute();
			} catch (\Exception $e2) {}
			
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}
}
