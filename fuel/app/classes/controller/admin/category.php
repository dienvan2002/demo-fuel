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

      public function action_index()
      {
            // Kiểm tra quyền đọc category theo chuẩn FuelPHP
            $this->require_permission('categories', 'read');
            
            // Lấy danh sách categories từ Service
            $categories = Service_Category::getAll(array(
                  'order_by' => 'created_at',
                  'order_dir' => 'desc'
            ));
            
            $view = View::forge('admin/category/index', [
                  'categories' => $categories
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $view,
                  'custom_css' => 'assets/css/admin/categories.css'
            ]));
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
                        // Thêm thành công - redirect về danh sách
                        Session::set_flash('success', $result['message']);
                        Response::redirect('admin/category');
                        exit();
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

      public function action_edit($id)
      {
            // Kiểm tra quyền cập nhật category
            $this->require_permission('categories', 'update');
            
            $category = Service_Category::getById($id);
            if (!$category) {
                  Session::set_flash('error', 'Không tìm thấy danh mục');
                  Response::redirect('admin/category');
                  exit();
            }

            $success_message = null;
            $error_messages = null;

            if (Input::method() === 'POST') {
                  $data = Input::post();
                  $result = Service_Category::update($id, $data);

                  if ($result['success']) {
                        Session::set_flash('success', $result['message']);
                        Response::redirect('admin/category');
                        exit();
                  } else {
                        $error_messages = $result['errors'];
                  }
            }

            $view = View::forge('admin/category/edit', [
                  'category' => $category,
                  'success_message' => $success_message,
                  'error_messages' => $error_messages
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $view,
                  'custom_css' => 'assets/css/admin/categories.css'
            ]));
      }

      public function action_delete($id)
      {
            // Kiểm tra quyền xóa category
            $this->require_permission('categories', 'delete');
            
            $category = Service_Category::getById($id);
            if (!$category) {
                  Session::set_flash('error', 'Không tìm thấy danh mục');
                  Response::redirect('admin/category');
                  exit();
            }

            $result = Service_Category::delete($id);
            
            if ($result['success']) {
                  Session::set_flash('success', $result['message']);
            } else {
                  Session::set_flash('error', $result['message']);
            }
            
            Response::redirect('admin/category');
            exit();
      }

      public function action_show($id)
      {
            // Kiểm tra quyền đọc category
            $this->require_permission('categories', 'read');
            
            $category = Service_Category::getById($id);
            if (!$category) {
                  Session::set_flash('error', 'Không tìm thấy danh mục');
                  Response::redirect('admin/category');
                  exit();
            }

            $view = View::forge('admin/category/show', [
                  'category' => $category
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $view,
                  'custom_css' => 'assets/css/admin/categories.css'
            ]));
      }
}