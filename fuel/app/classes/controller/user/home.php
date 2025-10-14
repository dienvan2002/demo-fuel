<?php


class Controller_User_Home extends Controller_Base
{
    public function before()
    {
        parent::before();
        $this->require_login(); // Chỉ cần đăng nhập, không cần admin
    }

    public function action_index()
    {
        // Lấy thông tin user hiện tại
        $current_user = Service_Auth::getCurrentUser();
        if (!$current_user) {
            Session::set_flash('error', 'Không tìm thấy thông tin người dùng');
            Response::redirect('auth/login');
            exit();
        }

        // Lấy profile chi tiết
        $profile = Model_User::get_profile($current_user['id']);
        
        // Merge user info với profile để có đầy đủ thông tin
        $user_info = array_merge($current_user, $profile);
        
        // Lấy thống kê cơ bản (có thể mở rộng sau)
        $stats = [
            'total_orders' => 0, 
            'total_spent' => 0,  
            'cart_items' => 0    
        ];

        $view = View::forge('user/home', [
            'current_user' => $user_info,
            'profile' => $profile,
            'stats' => $stats
        ]);

        return Response::forge(View::forge('layouts/user/base', [
            'main_content' => $view,
            'title' => 'Trang chủ',
            'current_user' => $user_info
        ]));
    }
}