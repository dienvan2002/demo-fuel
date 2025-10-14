<?php

/**
 * Service_User - Business Logic cho User Management
 * Chứa tất cả logic xử lý nghiệp vụ liên quan đến User
 */
class Service_User
{
    /**
     * Validate dữ liệu user
     */
    public static function validate($data, $is_update = false)
    {
        $errors = [];

        // Validate username (chỉ khi tạo mới hoặc có thay đổi)
        if (!$is_update || isset($data['username'])) {
            if (empty($data['username'])) {
                $errors[] = 'Tên đăng nhập không được để trống';
            }
        }

        // Validate name (luôn cần thiết)
        if (empty($data['name'])) {
            $errors[] = 'Tên người dùng không được để trống';
        }

        // Validate email (chỉ khi tạo mới hoặc có thay đổi)
        if (!$is_update || isset($data['email'])) {
            if (empty($data['email'])) {
                $errors[] = 'Email không được để trống';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }
        }

        // Validate password chỉ khi tạo mới hoặc có password
        if (isset($data['password']) && !empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
        }

        return $errors;
    }

    /**
     * Tạo user mới
     */
    public static function create($data)
    {
        $errors = self::validate($data, false); // false = create mode
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Kiểm tra username đã tồn tại chưa
        $existing = DB::select('id')
            ->from('users')
            ->where('username', $data['username'])
            ->execute()
            ->as_array();

        if (count($existing) > 0) {
            return [
                'success' => false,
                'errors' => ['Tên đăng nhập đã tồn tại']
            ];
        }

        // Kiểm tra email đã tồn tại chưa
        $existing_email = DB::select('id')
            ->from('users')
            ->where('email', $data['email'])
            ->execute()
            ->as_array();

        if (count($existing_email) > 0) {
            return [
                'success' => false,
                'errors' => ['Email đã tồn tại']
            ];
        }

        try {
            // Tạo user với Auth::create_user()
            $user_id = Auth::create_user(
                $data['username'],
                $data['password'],
                $data['email'],
                isset($data['group']) && $data['group'] == 100 ? 100 : 1 // Admin = 100, User = 1
            );

            // Lưu thông tin bổ sung vào profile_fields
            $profile_data = array(
                'name' => $data['name'],
                'phone' => isset($data['phone']) ? $data['phone'] : '',
                'gender' => isset($data['gender']) ? (int)$data['gender'] : 0,
                'avt' => isset($data['avt']) ? $data['avt'] : '',
            );

            DB::update('users')
                ->set(array('profile_fields' => serialize($profile_data)))
                ->where('id', $user_id)
                ->execute();

            return [
                'success' => true,
                'message' => 'Tạo người dùng thành công',
                'user_id' => $user_id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'errors' => ['Lỗi khi tạo người dùng: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Cập nhật thông tin user
     */
    public static function update($id, $data)
    {
        $user = self::getById($id);
        if (!$user) {
            return [
                'success' => false,
                'errors' => ['Không tìm thấy người dùng']
            ];
        }

        // Chỉ validate username và email nếu có thay đổi
        $validate_data = $data;
        if ($data['username'] == $user['username']) {
            unset($validate_data['username']);
        }
        if ($data['email'] == $user['email']) {
            unset($validate_data['email']);
        }

        $errors = self::validate($validate_data, true); // true = update mode
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Kiểm tra username trùng (nếu có thay đổi)
        if (isset($data['username'])) {
            $existing = DB::select('id')
                ->from('users')
                ->where('username', $data['username'])
                ->where('id', '!=', $id)
                ->execute()
                ->as_array();

            if (count($existing) > 0) {
                return [
                    'success' => false,
                    'errors' => ['Tên đăng nhập đã tồn tại']
                ];
            }
        }

        // Kiểm tra email trùng (nếu có thay đổi)
        if (isset($data['email'])) {
            $existing_email = DB::select('id')
                ->from('users')
                ->where('email', $data['email'])
                ->where('id', '!=', $id)
                ->execute()
                ->as_array();

            if (count($existing_email) > 0) {
                return [
                    'success' => false,
                    'errors' => ['Email đã tồn tại']
                ];
            }
        }

        try {
            // Cập nhật thông tin cơ bản
            $update_data = [];
            if (isset($data['username'])) $update_data['username'] = $data['username'];
            if (isset($data['email'])) $update_data['email'] = $data['email'];
            if (isset($data['group'])) $update_data['group'] = $data['group'] == 100 ? 100 : 1;

            if (!empty($update_data)) {
                DB::update('users')
                    ->set($update_data)
                    ->where('id', $id)
                    ->execute();
            }

            // Cập nhật profile_fields
            $profile_data = array(
                'name' => $data['name'],
                'phone' => isset($data['phone']) ? $data['phone'] : '',
                'gender' => isset($data['gender']) ? (int)$data['gender'] : 0,
                'avt' => isset($data['avt']) ? $data['avt'] : '',
            );

            DB::update('users')
                ->set(array('profile_fields' => serialize($profile_data)))
                ->where('id', $id)
                ->execute();

            // Cập nhật password nếu có
            if (isset($data['password']) && !empty($data['password'])) {
                $new_hash = Auth::hash_password($data['password']);
                DB::update('users')
                    ->set(array('password' => $new_hash))
                    ->where('id', $id)
                    ->execute();
            }

            return [
                'success' => true,
                'message' => 'Cập nhật người dùng thành công'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'errors' => ['Lỗi khi cập nhật: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Xóa user
     */
    public static function delete($id)
    {
        $user = self::getById($id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy người dùng'
            ];
        }

        // Không cho phép xóa admin cuối cùng
        if ($user['group'] == 100) {
            $admin_count = DB::select('id')
                ->from('users')
                ->where('group', 100)
                ->execute()
                ->count();

            if ($admin_count <= 1) {
                return [
                    'success' => false,
                    'message' => 'Không thể xóa admin cuối cùng'
                ];
            }
        }

        try {
            DB::delete('users')->where('id', $id)->execute();
            return [
                'success' => true,
                'message' => 'Xóa người dùng thành công'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách tất cả users
     */
    public static function getAll($options = [])
    {
        $query = DB::select('*')->from('users');

        // Order by
        $order_by = isset($options['order_by']) ? $options['order_by'] : 'created_at';
        $order_dir = isset($options['order_dir']) ? $options['order_dir'] : 'desc';
        $query->order_by($order_by, $order_dir);

        // Limit
        if (isset($options['limit'])) {
            $query->limit($options['limit']);
        }

        // Offset
        if (isset($options['offset'])) {
            $query->offset($options['offset']);
        }

        $users = $query->execute()->as_array();

        // Decode profile_fields và format data
        foreach ($users as &$user) {
            $profile = $user['profile_fields'] ? unserialize($user['profile_fields']) : [];
            $user['name'] = isset($profile['name']) ? $profile['name'] : '';
            $user['phone'] = isset($profile['phone']) ? $profile['phone'] : '';
            $user['gender'] = isset($profile['gender']) ? $profile['gender'] : 0;
            $user['avt'] = isset($profile['avt']) ? $profile['avt'] : '';
            $user['group_name'] = $user['group'] == 100 ? 'Admin' : 'User';
        }

        return $users;
    }

    /**
     * Lấy thông tin user theo ID
     */
    public static function getById($id)
    {
        $user = DB::select('*')
            ->from('users')
            ->where('id', $id)
            ->execute()
            ->as_array();

        if (empty($user)) {
            return null;
        }

        $user = $user[0];
        $profile = $user['profile_fields'] ? unserialize($user['profile_fields']) : [];
        
        $user['name'] = isset($profile['name']) ? $profile['name'] : '';
        $user['phone'] = isset($profile['phone']) ? $profile['phone'] : '';
        $user['gender'] = isset($profile['gender']) ? $profile['gender'] : 0;
        $user['avt'] = isset($profile['avt']) ? $profile['avt'] : '';
        $user['group_name'] = $user['group'] == 100 ? 'Admin' : 'User';

        return $user;
    }

    /**
     * Tìm kiếm users
     */
    public static function search($keyword, $options = [])
    {
        $query = DB::select('*')->from('users');

        if (!empty($keyword)) {
            $query->where_open()
                ->where('username', 'LIKE', '%' . $keyword . '%')
                ->or_where('email', 'LIKE', '%' . $keyword . '%')
                ->where_close();
        }

        // Order by
        $order_by = isset($options['order_by']) ? $options['order_by'] : 'created_at';
        $order_dir = isset($options['order_dir']) ? $options['order_dir'] : 'desc';
        $query->order_by($order_by, $order_dir);

        $users = $query->execute()->as_array();

        // Decode profile_fields và filter by name
        $filtered_users = [];
        foreach ($users as $user) {
            $profile = $user['profile_fields'] ? unserialize($user['profile_fields']) : [];
            $name = isset($profile['name']) ? $profile['name'] : '';
            
            // Nếu có keyword, kiểm tra cả name
            if (empty($keyword) || stripos($name, $keyword) !== false) {
                $user['name'] = $name;
                $user['phone'] = isset($profile['phone']) ? $profile['phone'] : '';
                $user['gender'] = isset($profile['gender']) ? $profile['gender'] : 0;
                $user['avt'] = isset($profile['avt']) ? $profile['avt'] : '';
                $user['group_name'] = $user['group'] == 100 ? 'Admin' : 'User';
                $filtered_users[] = $user;
            }
        }

        return $filtered_users;
    }

    /**
     * Lấy dropdown options cho groups
     */
    public static function getGroupOptions()
    {
        return [
            1 => 'User',
            100 => 'Admin'
        ];
    }

    /**
     * Lấy dropdown options cho gender
     */
    public static function getGenderOptions()
    {
        return [
            0 => 'Nam',
            1 => 'Nữ'
        ];
    }
}
