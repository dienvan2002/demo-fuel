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
		
		\Log::info(' User logged out successfully');
		
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
	public static function validate_user($data)
	{
		// Validate dữ liệu
		if (empty($data['username']) || empty($data['password']) || empty($data['name'])) {
			return [
				'status' => false,
				'message' => 'Thiếu thông tin bắt buộc',
				'user_id' => null
			];
		};
		// Kiểm tra username đã tồn tại chưa
		$existing_user = \DB::select('id')
			->from('users')
			->where('username', $data['username'])
			->execute()
			->as_array();

		if (count($existing_user) > 0) {
			return [
				'status' => false,
				'message' => 'Tên đăng nhập đã tồn tại',
				'user_id' => null
			];
		}
		return [
			'status'=> true,
			'message'=> 'Hợp lệ',

		];

	}
	public static function register($data)
	{
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

			\Log::info(' User registered successfully: ' . $data['username']);

			return [
				'success' => true,
				'message' => 'Tạo tài khoản thành công',
				'user_id' => $user_id
			];
		} catch (\Exception $e) {
			\Log::error(' Registration failed: ' . $e->getMessage());
			
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
	public static function sendmail($email1, $name, $otp){
		// Tạo mã 6 số ngẫu nhiên
		$code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
		
		// Lưu mã vào Session với thời gian hết hạn (15 phút)
		\Session::set('otp_code', $code);
		\Session::set('otp_email', $email1);
		\Session::set('otp_expires', time() + (15 * 60)); // 15 phút
		
		// Gửi email với mã
		$email = \Email::forge(
            array(
                'driver' => 'smtp',
            )
        );
        $email->from('nguyenduydien02@gmail.com', 'Nguyễn Duy Diện');
        $email->to($email1, $name);
        $email->subject('Mã xác thực đăng ký tài khoản');
        $email->body('Xin chào ' . $name . ',

				Mã xác thực của bạn là: ' . $code . '

				Mã này có hiệu lực trong 15 phút.
				Vui lòng không chia sẻ mã này với ai khác.

				Trân trọng,
				Demo Fuel Team');
        try {
            $email->send();
			return array(
				'status' => 'success',
				'message' => 'Mã xác thực đã được gửi đến email của bạn'
			);
        } catch (\EmailValidationFailedException $e) {
			return array(
				'status' => 'error',
				'message' => 'Lỗi định dạng email'
			);
        } catch (\EmailSendingFailedException $e) {
			return array( 
				'status'=> 'error',
				'message'=> 'Không thể gửi email. Vui lòng thử lại sau.'
				);
        } 
	}
	
	/**
	 * Xác thực mã OTP từ Session
	 */
	public static function verify_otp($email, $code)
	{
		if (empty($email) || empty($code)) {
			return array(
				'status' => 'error',
				'message' => 'Vui lòng nhập đầy đủ thông tin'
			);
		}
		
		// Kiểm tra email có khớp không
		$session_email = \Session::get('otp_email');
		if ($session_email !== $email) {
			return array(
				'status' => 'error',
				'message' => 'Email không khớp'
			);
		}
		
		// Kiểm tra mã có hết hạn không
		$expires = \Session::get('otp_expires');
		if (time() > $expires) {
			// Xóa session cũ
			\Session::delete('otp_code');
			\Session::delete('otp_email');
			\Session::delete('otp_expires');
			return array(
				'status' => 'error',
				'message' => 'Mã xác thực đã hết hạn'
			);
		}
		
		// Kiểm tra mã có đúng không
		$session_code = \Session::get('otp_code');
		if ($session_code === $code) {
			// Xóa session sau khi verify thành công
			\Session::delete('otp_code');
			\Session::delete('otp_email');
			\Session::delete('otp_expires');
			return array(
				'status' => 'success',
				'message' => 'Xác thực thành công'
			);
		} else {
			return array(
				'status' => 'error',
				'message' => 'Mã xác thực không đúng'
			);
		}
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
			return 'user/products';
		}
	}
}
