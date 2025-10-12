<?php

/**
 * Auth Service - Business logic cho authentication
 * Theo nguyên tắc Separation of Concerns
 */
class Service_Auth
{
	/**
	 * Xử lý đăng nhập
	 * 
	 * @param string $username
	 * @param string $password
	 * @param bool $remember
	 * @return array ['success' => bool, 'message' => string, 'redirect' => string|null, 'debug' => array]
	 */
	public static function login($username, $password, $remember = false)
	{
		// Validate input
		if (empty($username) || empty($password)) {
			return [
				'success' => false,
				'message' => 'Vui lòng nhập đầy đủ thông tin',
				'redirect' => null
			];
		}

		// Attempt login với SimpleAuth
		$login_result = \Auth::instance()->login($username, $password);
		
		if (!$login_result) {
			\Log::error('Login failed for username: ' . $username);
			return [
				'success' => false,
				'message' => 'Tên đăng nhập hoặc mật khẩu không đúng',
				'redirect' => null
			];
		}

		// Login successful
		\Log::info('Login successful for user: ' . $username);
		
		// Xử lý remember me
		if ($remember) {
			\Auth::remember_me();
		} else {
			\Auth::dont_remember_me();
		}
		
		// Xác định redirect URL dựa trên quyền
		$redirect = self::getRedirectUrl();
		
		return [
			'success' => true,
			'message' => 'Đăng nhập thành công',
			'redirect' => $redirect
		];
	}

	/**
	 * Xử lý đăng xuất
	 * 
	 * @return array ['success' => bool, 'message' => string]
	 */
	public static function logout()
	{
		// Xóa remember me cookie
		\Auth::dont_remember_me();
		
		// Logout khỏi Auth system
		\Auth::logout();
		
		// Xóa toàn bộ session data
		\Session::destroy();
		
		\Log::info('✅ User logged out successfully');
		
		return [
			'success' => true,
			'message' => 'Đăng xuất thành công! Hẹn gặp lại bạn.'
		];
	}

	/**
	 * Xử lý đăng ký user mới
	 * 
	 * @param array $data
	 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
	 */
	public static function register($data)
	{
		// Validate dữ liệu
		if (empty($data['username']) || empty($data['password']) || empty($data['name'])) {
			return [
				'success' => false,
				'message' => 'Thiếu thông tin bắt buộc',
				'user_id' => null
			];
		}

		// Kiểm tra username đã tồn tại chưa
		$existing_user = \DB::select('id')
			->from('users')
			->where('username', $data['username'])
			->execute()
			->as_array();

		if (count($existing_user) > 0) {
			return [
				'success' => false,
				'message' => 'Tên đăng nhập đã tồn tại',
				'user_id' => null
			];
		}

		// Tạo user mới với Auth::create_user()
		try {
			$user_id = \Auth::create_user(
				$data['username'],
				$data['password'],
				isset($data['email']) ? $data['email'] : '',
				1 // Group 1 = User thường (không phải admin)
			);

			// Lưu thông tin bổ sung vào profile_fields
			$profile_data = array(
				'name' => $data['name'],
				'phone' => isset($data['phone']) ? $data['phone'] : '',
				'gender' => isset($data['gender']) ? $data['gender'] : 0,
				'avt' => isset($data['avt']) ? $data['avt'] : '',
			);

			\DB::update('users')
				->set(array('profile_fields' => serialize($profile_data)))
				->where('id', $user_id)
				->execute();

			\Log::info('✅ User registered successfully: ' . $data['username']);

			return [
				'success' => true,
				'message' => 'Tạo tài khoản thành công',
				'user_id' => $user_id
			];
		} catch (\Exception $e) {
			\Log::error('❌ Registration failed: ' . $e->getMessage());
			
			return [
				'success' => false,
				'message' => 'Không thể tạo tài khoản: ' . $e->getMessage(),
				'user_id' => null
			];
		}
	}

	/**
	 * Kiểm tra user đã đăng nhập chưa
	 * 
	 * @return bool
	 */
	public static function check()
	{
		return \Auth::check();
	}

	/**
	 * Kiểm tra user có phải admin không
	 * 
	 * @return bool
	 */
	public static function isAdmin()
	{
		return \Auth::member(100);
	}

	/**
	 * Lấy thông tin user hiện tại
	 * 
	 * @return array|null
	 */
	public static function getCurrentUser()
	{
		// Lấy user ID từ Auth driver
		list($driver, $user_id) = \Auth::instance()->get_user_id();
		
		if ($user_id) {
			// Query database để lấy thông tin user
			$user = \DB::select('*')
				->from('users')
				->where('id', $user_id)
				->execute()
				->current();
			
			return $user;
		}
		
		return null;
	}

	/**
	 * Xác định URL redirect sau khi login
	 * 
	 * @return string
	 */
	protected static function getRedirectUrl()
	{
		if (\Auth::member(100)) {
			return 'admin/home';
		} else {
			return 'user/home';
		}
	}
}
