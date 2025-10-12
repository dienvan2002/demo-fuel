<?php

/**
 * User Home Controller - Trang chủ cho user thường
 * Theo chuẩn FuelPHP chính thức
 */
class Controller_User_Home extends Controller_Base
{
      public function before()
      {
            parent::before();
            
            // Kiểm tra authentication theo chuẩn FuelPHP
            $this->require_login();
            
            // Nếu là admin, redirect về admin
            if (Auth::member(100)) {
                  Response::redirect('admin/home');
            }
      }

      public function action_index()
      {
            // Lấy thông tin user hiện tại
            $current_user = Model_User::get_current_user();
            $current_profile = Model_User::get_profile();
            
            $main_content = View::forge('user/home', [
                  'current_user' => $current_user,
                  'current_profile' => $current_profile
            ]);

            return Response::forge(View::forge('layouts/user/base', [
                  'main_content' => $main_content,
                  'current_user' => $current_user
            ]));
      }
}
