<?php

abstract class Controller_Base extends Controller
{
	
	public function before()
	{
		parent::before();
		
	}


	public function after($response)
	{
		return parent::after($response);
	}

	
	protected function require_login()
	{
		if (!Auth::check()) {
			Session::set_flash('error', 'Vui lòng đăng nhập để tiếp tục');
			Response::redirect('auth/login');
			exit();
		}
	}

	
	protected function require_admin()
	{
		$this->require_login();
		
		if (!Auth::member(100)) { // Group 100 = Admin
			Session::set_flash('error', 'Bạn không có quyền truy cập trang này');
			Response::redirect('user/home');
			exit();
		}
	}

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
