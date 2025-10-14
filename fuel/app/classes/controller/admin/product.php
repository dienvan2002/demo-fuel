<?php

class Controller_Admin_Product extends Controller_Base
{
    public function before()
    {
        parent::before();
        
        // Kiểm tra quyền admin
        $this->require_admin();
    }

    public function action_index()
    {
        // Kiểm tra quyền đọc sản phẩm
        $this->require_permission('products', 'read');
        
        // Lấy danh sách sản phẩm từ Service (admin mode - tất cả sản phẩm)
        $result = Service_Product::getAll(array(
            'order_by' => 'created_at',
            'order_dir' => 'desc',
            'admin_mode' => true
        ));
        $products = $result['products'] ?? [];
        
        $view = View::forge('admin/product/index', [
            'products' => $products
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/products.css'
        ]));
    }

    public function action_create()
    {
        // Kiểm tra quyền tạo sản phẩm
        $this->require_permission('products', 'create');
        
        $success_message = null;
        $error_messages = null;

        if (Input::method() === 'POST') {
            $result = Service_Product::create(Input::post());

            if ($result['success']) {
                // Tạo thành công - redirect về danh sách
                Session::set_flash('success', $result['message']);
                Response::redirect('admin/product');
                exit();
            } else {
                // Có lỗi - hiển thị lỗi
                $error_messages = $result['errors'];
            }
        }

        // Lấy danh sách categories cho dropdown
        $categories = Service_Category::getDropdown();

        $view = View::forge('admin/product/create', [
            'success_message' => $success_message,
            'error_messages' => $error_messages,
            'categories' => $categories
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/products.css'
        ]));
    }

    public function action_edit($id)
    {
        // Kiểm tra quyền cập nhật sản phẩm
        $this->require_permission('products', 'update');
        
        $product = Service_Product::getById($id);
        if (!$product) {
            Session::set_flash('error', 'Không tìm thấy sản phẩm');
            Response::redirect('admin/product');
            exit();
        }

        $success_message = null;
        $error_messages = null;

        if (Input::method() === 'POST') {
            $data = Input::post();
            $result = Service_Product::update($id, $data);

            if ($result['success']) {
                Session::set_flash('success', $result['message']);
                Response::redirect('admin/product');
                exit();
            } else {
                $error_messages = $result['errors'];
            }
        }
        // Lấy danh sách categories cho dropdown
        $categories = Service_Category::getDropdown();

        $view = View::forge('admin/product/edit', [
            'product' => $product,
            'success_message' => $success_message,
            'error_messages' => $error_messages,
            'categories' => $categories
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/products.css'
        ]));
    }

    
    public function action_delete($id)
    {
        // Kiểm tra quyền xóa sản phẩm
        $this->require_permission('products', 'delete');
        
        $product = Service_Product::getById($id);
        if (!$product) {
            Session::set_flash('error', 'Không tìm thấy sản phẩm');
            Response::redirect('admin/product');
            exit();
        }

        $result = Service_Product::delete($id);
        
        if ($result['success']) {
            Session::set_flash('success', $result['message']);
        } else {
            Session::set_flash('error', $result['message']);
        }
        
        Response::redirect('admin/product');
        exit();
    }

    
    public function action_show($id)
    {
        // Kiểm tra quyền đọc sản phẩm
        $this->require_permission('products', 'read');
        
        $product = Service_Product::getById($id);
        if (!$product) {
            Session::set_flash('error', 'Không tìm thấy sản phẩm');
            Response::redirect('admin/product');
            exit();
        }

        $view = View::forge('admin/product/show', [
            'product' => $product
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/products.css'
        ]));
    }
}