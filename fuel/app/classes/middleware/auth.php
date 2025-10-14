<?php

class Middleware_Auth
{

	public static function check_login()
	{
		if (!Auth::check()) {
			Session::set_flash('error', 'Vui lòng đăng nhập để tiếp tục');
			Response::redirect('auth/login');
		}
	}

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

	public static function check_user()
	{
		// Trước tiên kiểm tra đã đăng nhập chưa
		self::check_login();

		// Nếu là admin, redirect về admin
		if (Auth::member(100)) {
			Response::redirect('admin/home');
		}
	}

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

	public static function check_permission($area, $permission)
	{
		self::check_login();
		
		if (!Auth::has_access($area . '.' . $permission)) {
			Session::set_flash('error', 'Bạn không có quyền thực hiện hành động này');
			Response::redirect_back();
		}
	}
}
