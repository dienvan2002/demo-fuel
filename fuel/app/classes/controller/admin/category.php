<?php

/**
 * Admin Category Controller - Quản lý danh mục
 * Thin Controller - Chỉ xử lý HTTP request/response
 * Business logic được delegate cho Model
 * Theo chuẩn FuelPHP chính thức
 */
class Controller_Admin_Category extends Controller_Base
{
      public function before()
      {
            parent::before();
            
            // Kiểm tra quyền admin theo chuẩn FuelPHP
            $this->require_admin();
      }

      public function action_create()
      {
            // Kiểm tra quyền tạo category theo chuẩn FuelPHP
            $this->require_permission('categories', 'create');
            
            $success_message = null;
            $error_messages = null;

            if (Input::method() === 'POST') {
                  $result = Service_Category::create(Input::post());

                  if ($result['success']) {
                        // Thêm thành công - set message để hiển thị
                        $success_message = $result['message'];
                  } else {
                        // Có lỗi - set error messages
                        $error_messages = $result['errors'];
                  }
            }

            $main_content = View::forge('admin/category/create', [
                  'success_message' => $success_message,
                  'error_messages' => $error_messages
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/create.css'
            ]));
      }
}