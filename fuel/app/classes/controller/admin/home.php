<?php

class Controller_Admin_Home extends Controller_Base
{
      public function before()
      {
            parent::before();
            
            // Kiểm tra quyền admin theo chuẩn FuelPHP
            $this->require_admin();
      }

      public function action_index()
      {
            // Lấy thông tin user hiện tại
            $current_user = Model_User::get_current_user();
            $current_profile = Model_User::get_profile();
            
            $main_content = View::forge('admin/home', [
                  'current_user' => $current_user,
                  'current_profile' => $current_profile
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/home.css'
            ]));
      }
}