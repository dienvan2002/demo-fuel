<?php

/**
 * Model_User - Wrapper cho SimpleAuth User
 * Sử dụng SimpleAuth hoàn toàn, không extend ORM
 */
class Model_User
{


	/**
	 * Kiểm tra user có phải admin không (dựa trên group)
	 * Theo chuẩn FuelPHP
	 */
	public static function is_admin()
	{
		return Auth::member(100); // Group 100 = Administrators
	}

	/**
	 * Lấy thông tin user hiện tại từ SimpleAuth
	 * Theo chuẩn FuelPHP
	 */
	public static function get_current_user()
	{
		// Lấy user ID từ Auth driver
		list($driver, $user_id) = Auth::instance()->get_user_id();
		
		if ($user_id) {
			// Query database để lấy thông tin user
			$user = DB::select('*')
				->from('users')
				->where('id', $user_id)
				->execute()
				->current();
			
			return $user;
		}
		
		return null;
	}

	/**
	 * Tạo user mới với SimpleAuth theo chuẩn FuelPHP
	 */
	public static function create_user($data)
	{
		// Validate dữ liệu
		if (empty($data['username']) || empty($data['password']) || empty($data['name'])) {
			return ['success' => false, 'message' => 'Thiếu thông tin bắt buộc'];
		}

		// Tạo user mới với Auth::create_user() theo chuẩn FuelPHP
		try {
			$user_id = Auth::create_user(
				$data['username'],
				$data['password'],
				isset($data['email']) ? $data['email'] : '',
				isset($data['isAdmin']) && $data['isAdmin'] ? 100 : 1 // Group 100 = Admin, 1 = User
			);

			// Lưu thông tin bổ sung vào profile_fields
			$profile_data = array(
				'name' => $data['name'],
				'phone' => isset($data['phone']) ? $data['phone'] : '',
				'gender' => isset($data['gender']) ? $data['gender'] : 0,
				'avt' => isset($data['avt']) ? $data['avt'] : '',
			);

			// Cập nhật profile_fields thông qua DB
			DB::update('users')
				->set(array('profile_fields' => serialize($profile_data)))
				->where('id', $user_id)
				->execute();

			return ['success' => true, 'message' => 'Tạo tài khoản thành công', 'user_id' => $user_id];
		} catch (\Exception $e) {
			return ['success' => false, 'message' => 'Không thể tạo tài khoản: ' . $e->getMessage()];
		}
	}

	/**
	 * Lấy thông tin profile của user
	 */
	public static function get_profile($user_id = null)
	{
		if (!$user_id) {
			// Sử dụng get_current_user() thay vì Auth::get_user() cũ
			$user = self::get_current_user();
			if (!$user) return null;
			$user_id = $user['id'];
		}

		$result = DB::select('profile_fields')
			->from('users')
			->where('id', $user_id)
			->execute();

		if (empty($result)) return null;

		$profile_fields = $result[0]['profile_fields'];
		return $profile_fields ? unserialize($profile_fields) : array();
	}

	/**
	 * Cập nhật thông tin profile của user
	 */
	public static function update_profile($data, $user_id = null)
	{
		if (!$user_id) {
			// Sử dụng get_current_user() thay vì Auth::get_user() cũ
			$user = self::get_current_user();
			if (!$user) return false;
			$user_id = $user['id'];
		}

		try {
			DB::update('users')
				->set(array('profile_fields' => serialize($data)))
				->where('id', $user_id)
				->execute();

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}