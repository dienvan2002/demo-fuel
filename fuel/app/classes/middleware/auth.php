<?php

/**
 * Auth Middleware - Kiểm tra authentication và phân quyền với SimpleAuth
 * Theo chuẩn FuelPHP chính thức
 */
class Middleware_Auth
{
	/**
	 * Kiểm tra user đã đăng nhập chưa
	 * Nếu chưa đăng nhập, redirect về trang login
	 */
	public static function check_login()
	{
		if (!Auth::check()) {
			Session::set_flash('error', 'Vui lòng đăng nhập để tiếp tục');
			Response::redirect('auth/login');
		}
	}

	/**
	 * Kiểm tra user có phải admin không (Group 100)
	 * Nếu không phải admin, redirect về trang user hoặc báo lỗi
	 */
	public static function check_admin()
	{
		// Trước tiên kiểm tra đã đăng nhập chưa
		self::check_login();

		// Sau đó kiểm tra có phải admin không (Group 100)
		if (!Auth::member(100)) {
			Session::set_flash('error', 'Bạn không có quyền truy cập trang này');
			Response::redirect('user/home');
		}
	}

	/**
	 * Kiểm tra user có phải user thường không
	 * Nếu là admin, redirect về admin panel
	 */
	public static function check_user()
	{
		// Trước tiên kiểm tra đã đăng nhập chưa
		self::check_login();

		// Nếu là admin, redirect về admin
		if (Auth::member(100)) {
			Response::redirect('admin/home');
		}
	}

	/**
	 * Kiểm tra user chưa đăng nhập (cho trang login/register)
	 * Nếu đã đăng nhập, redirect về trang phù hợp
	 */
	public static function check_guest()
	{
		if (Auth::check()) {
			if (Auth::member(100)) {
				Response::redirect('admin/home');
			} else {
				Response::redirect('user/home');
			}
		}
	}

	/**
	 * Kiểm tra quyền cụ thể với SimpleAuth ACL
	 * Ví dụ: check_permission('products', 'create')
	 */
	public static function check_permission($area, $permission)
	{
		self::check_login();
		
		if (!Auth::has_access($area . '.' . $permission)) {
			Session::set_flash('error', 'Bạn không có quyền thực hiện hành động này');
			Response::redirect_back();
		}
	}
}
