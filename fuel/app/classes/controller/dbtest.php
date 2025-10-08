<?php

use Fuel\Core\Controller;
use Fuel\Core\DB;
use Fuel\Core\Database_Exception;


class Controller_Dbtest extends Controller
{
	public function action_index()
	{
		try {
			// Thực hiện truy vấn đơn giản để test kết nối
			$result = \DB::select()->from(\DB::expr('(SELECT VERSION() AS version) as test'))->execute();

			// Lấy kết quả và hiển thị
			$db_version = $result[0]['version'];
			return " Kết nối thành công! MySQL version: " . $db_version;
		} catch (\Database_Exception $e) {
			// Nếu lỗi, hiển thị thông báo lỗi
			return " Kết nối thất bại: " . $e->getMessage();
		}
	}
}