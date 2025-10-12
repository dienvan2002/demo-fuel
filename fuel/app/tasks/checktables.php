<?php

namespace Fuel\Tasks;

/**
 * Oil task để kiểm tra bảng users
 * Chạy: php oil r checktables
 */
class Checktables
{
	/**
	 * Kiểm tra bảng users có tồn tại không
	 */
	public function run()
	{
		try {
			echo "Đang kiểm tra các bảng trong database...\n\n";
			
			// Lấy danh sách tất cả bảng
			$tables = \DB::list_tables();
			
			echo "📋 Danh sách bảng:\n";
			foreach ($tables as $table) {
				echo "  - $table\n";
			}
			
			// Kiểm tra bảng users
			if (in_array('users', $tables)) {
				echo "\n✅ Bảng 'users' TỒN TẠI!\n\n";
				
				// Hiển thị cấu trúc bảng users
				echo "📊 Cấu trúc bảng 'users':\n";
				$columns = \DB::list_columns('users');
				
				foreach ($columns as $column => $info) {
					$type = isset($info['data_type']) ? $info['data_type'] : 'unknown';
					$length = isset($info['character_maximum_length']) ? '('.$info['character_maximum_length'].')' : '';
					echo "  - $column: $type$length\n";
				}
			} else {
				echo "\n❌ Bảng 'users' KHÔNG TỒN TẠI!\n";
				echo "Hãy chạy: php oil refine migrate --packages=auth\n";
			}
			
		} catch (\Exception $e) {
			echo "❌ Lỗi: " . $e->getMessage() . "\n";
		}
	}
}
