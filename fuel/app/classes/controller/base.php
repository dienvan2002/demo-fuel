<?php

/**
 * Base Controller - Controller cơ sở theo chuẩn FuelPHP
 * Chứa các method chung cho tất cả controllers
 */
abstract class Controller_Base extends Controller
{
	/**
	 * Before method - chạy trước mỗi action
	 * Theo chuẩn FuelPHP
	 */
	public function before()
	{
		parent::before();
		
		// Có thể thêm logic chung ở đây
		// Ví dụ: set timezone, load common data, etc.
	}

	/**
	 * After method - chạy sau mỗi action
	 * Theo chuẩn FuelPHP
	 */
	public function after($response)
	{
		return parent::after($response);
	}

	/**
	 * Kiểm tra user đã đăng nhập chưa
	 * Helper method theo chuẩn FuelPHP
	 */
	protected function require_login()
	{
		if (!Auth::check()) {
			Session::set_flash('error', 'Vui lòng đăng nhập để tiếp tục');
			Response::redirect('auth/login');
			exit();
		}
	}

	/**
	 * Kiểm tra user có phải admin không
	 * Helper method theo chuẩn FuelPHP
	 */
	protected function require_admin()
	{
		$this->require_login();
		
		if (!Auth::member(100)) { // Group 100 = Admin
			Session::set_flash('error', 'Bạn không có quyền truy cập trang này');
			Response::redirect('user/home');
			exit();
		}
	}

	/**
	 * Kiểm tra quyền cụ thể
	 * Helper method theo chuẩn FuelPHP
	 */
	protected function require_permission($area, $permission)
	{
		$this->require_login();
		
		if (!Auth::has_access($area . '.' . $permission)) {
			Session::set_flash('error', 'Bạn không có quyền thực hiện hành động này');
			Response::redirect_back();
			exit();
		}
	}
}
