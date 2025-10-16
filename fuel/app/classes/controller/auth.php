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
		
		// Xử lý form đăng ký
		if (Input::method() === 'POST') {
			$data = Input::post();
			
			// Validate dữ liệu
			$validation = Service_Auth::validate_user($data);
			
			// Validate thành công
			if($validation['status']) {
				// Lưu thông tin đăng ký vào session
				Session::set('pending_registration', $data);
				
				// Gửi OTP tới email
				$result = Service_Auth::sendmail($data['email'], $data['name'], '');
				
				// Gửi mail thành công
				if ($result['status'] === 'success') {
					Session::set_flash('success', $result['message']);
					Response::redirect('auth/verify');
					exit();
				} else {
					$error_message = $result['message'];
				}
			} else {
				$error_message = $validation['message'];
			}
		}

		$view = View::forge('auth/register', [
			'error_message' => $error_message,
			'success_message' => $success_message
		]);

		return Response::forge($view);
	}
	public function action_verify(){
		$error_message = null;
		$success_message = null;
		
		// Kiểm tra có email cần verify không
		$verify_email = Session::get('otp_email');
		if (!$verify_email) {
			Session::set_flash('error', 'Phiên làm việc đã hết hạn. Vui lòng đăng ký lại.');
			Response::redirect('auth/register');
			exit();
		}
		
		// Xử lý form verify
		if (Input::method() === 'POST') {
			$code = Input::post('code');
			// Gộp 6 input thành 1 mã
			if (is_array($code)) {
				$code = implode('', $code);
			}
			
			// Xác thực mã
			$result = Service_Auth::verify_otp($verify_email, $code);
			
			if ($result['status'] === 'success') {
				// Lấy thông tin đăng ký từ session
				$registration_data = Session::get('pending_registration');
				if ($registration_data) {
					// Tạo tài khoản
					$register_result = Service_Auth::register($registration_data);
					
					if ($register_result['success']) {
						// Xóa session
						Session::delete('pending_registration');
						
						Session::set_flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
						Response::redirect('auth/login');
						exit();
					} else {
						$error_message = $register_result['message'];
					}
				} else {
					$error_message = 'Không tìm thấy thông tin đăng ký';
				}
			} else {
				$error_message = $result['message'];
			}
		}
		
		$view = View::forge('auth/verify', [
			'error_message' => $error_message,
			'success_message' => $success_message,
			'email' => $verify_email
		]);
		return Response::forge($view);
	}
}
