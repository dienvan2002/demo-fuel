<?php

use Fuel\Core\Response;
use Fuel\Core\View;

class Controller_Auth extends Controller_Base
{
	
	public function action_login()
	{
		$error_message = null;
		$success_message = null;

		try {
			// Nếu đã đăng nhập, redirect về trang chủ
			if (Service_Auth::check()) {
				$redirect = Service_Auth::isAdmin() ? 'admin/home' : 'user/products';
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
			'success_message' => $success_message,
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
		$otp= 123456;
		// Xử lý form đăng ký
		if (Input::method() === 'POST') {
			$data = Input::post();
			
			//validate dữ liệu
			$validation = Service_Auth::validate_user($data);
			//validate thành công
			if($validation['status']) {
				//gửi otp tới email
				$result = Service_Auth::sendmail($data['email'], $data['name'], $otp);
				//gửi mail thành công
				if ($result['status'] === 'success') {
					$success_message = $result['message'];
					Response::redirect('auth/verify');
					exit();
				} else if ($result['status'] === 'error')  {
					$error_message = $result['message'];
				}
			
			}else{
				$error_message = $validation['message'];
				echo $error_message;
				Response::redirect('auth/login');
				
			}
			echo ('debug');
		var_dump($error_message);
		var_dump($success_message);
		exit();
		}
		

		$view = View::forge('auth/register', [
			'error_message' => $error_message,
			'success_message' => $success_message
		]);

		return Response::forge($view);
	}
	public function action_verify(){
		$view = View::forge('auth/verify');
		return Response::forge($view);
	}
}
