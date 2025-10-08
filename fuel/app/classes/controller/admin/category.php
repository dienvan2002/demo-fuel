<?php

/**
 * Thin Controller - Chỉ xử lý HTTP request/response
 * Business logic được delegate cho Model
 */
class Controller_Admin_Category extends Controller
{
      public function action_create()
      {
            if (Input::method() === 'POST') {
                  $result = Service_Category::create(Input::post());

                  if ($result['success']) {
                        Session::set_flash('success', 'Thêm danh mục thành công');
                        Response::redirect('admin/category/create');
                  } else {
                        Session::set_flash('errors', $result['errors']);
                  }
            }

            $main_content = View::forge('admin/category/create');
            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/create.css'
            ]));
      }
}