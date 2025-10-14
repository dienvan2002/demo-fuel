<?php

class Controller_Auth extends Controller_Base
{
	
	public function action_login()
	{
		$error_message = null;
		$success_message = null;

		try {
			// Nếu đã đăng nhập, redirect về trang chủ
			if (Service_Auth::check()) {
				$redirect = Service_Auth::isAdmin() ? 'admin/home' : 'products';
				Response::redirect($redirect);
				exit();
			}

			// Xử lý form đăng nhập
			if (Input::method() === 'POST') {
				$username = Input::post('username');
				$password = Input::post('password');
				$remember = Input::post('remember', false);

				// Gọi Service để xử lý login logic
				$result = Service_Auth::login($username, $password, $remember);
				
				if ($result['success']) {
					// Set flash message để hiển thị sau khi redirect
					Session::set_flash('success',   $result['message'] . '! Chào mừng bạn đã quay trở lại.');
					
					$redirect_url = Uri::create($result['redirect']);
					Response::redirect($redirect_url);
					exit();
				} else {
					$error_message = $result['message'];
				}
			}

		} catch (\Exception $e) {
			$error_message = ' Lỗi hệ thống: ' . $e->getMessage();
			Log::error('Login exception: ' . $e->getMessage());
		}

		$view = View::forge('auth/login', [
			'error_message' => $error_message,
			'success_message' => $success_message
		]);

		return Response::forge($view);
	}

	
	public function action_logout()
	{
		// Gọi Service để xử lý logout logic
		$result = Service_Auth::logout();
		
		Session::set_flash('success', $result['message']);
		Response::redirect('auth/login');
		exit();
	}


	public function action_register()
	{
		$error_message = null;
		$success_message = null;

		// Xử lý form đăng ký
		if (Input::method() === 'POST') {
			$data = Input::post();
			
			// Gọi Service để xử lý register logic
			$result = Service_Auth::register($data);
			
			if ($result['success']) {
				Session::set_flash('success', $result['message']);
				Response::redirect('auth/login');
				exit();
			} else {
				$error_message = $result['message'];
			}
		}

		$view = View::forge('auth/register', [
			'error_message' => $error_message,
			'success_message' => $success_message
		]);

		return Response::forge($view);
	}
}
