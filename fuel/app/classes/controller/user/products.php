<?php

class Controller_User_Products extends Controller_Base
{
    public function before()
    {
        parent::before();
        $this->require_login();
    }

    public function action_index()
    {
        // Lấy thông tin user hiện tại
        $current_user = Service_Auth::getCurrentUser();
        if (!$current_user) {
            Session::set_flash('error', 'Vui lòng đăng nhập');
            Response::redirect('auth/login');
            exit();
        }

        // Merge user info với profile
        $profile = Model_User::get_profile($current_user['id']);
        $user_info = array_merge($current_user, $profile);
        
        // Lấy tham số từ URL
        $page = Input::get('page', 1);
        $limit = 12; // 12 sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;
        
        $category_id = Input::get('category', '');
        $keyword = Input::get('search', '');
        
        // Lấy danh sách categories cho filter
        $categories = Service_Category::getDropdown();
        
        // Lấy danh sách sản phẩm
        $products = [];
        $total_products = 0;
        
        if (!empty($keyword)) {
            // Tìm kiếm sản phẩm
            $search_result = Service_Product::search($keyword, $category_id, [
                'limit' => $limit,
                'offset' => $offset,
                'order_by' => 'created_at',
                'order_dir' => 'desc'
            ]);
            $products = $search_result['products'] ?? [];
            $total_products = $search_result['total'] ?? 0;
        } elseif (!empty($category_id)) {
            // Lọc theo category
            $category_products = Service_Product::getByCategory($category_id, [
                'limit' => $limit,
                'offset' => $offset,
                'order_by' => 'created_at',
                'order_dir' => 'desc'
            ]);
            $products = $category_products['products'] ?? [];
            $total_products = $category_products['total'] ?? 0;
        } else {
            // Lấy tất cả sản phẩm
            $all_products = Service_Product::getAll([
                'limit' => $limit,
                'offset' => $offset,
                'order_by' => 'created_at',
                'order_dir' => 'desc'
            ]);
            $products = $all_products['products'] ?? $all_products;
            $total_products = count($products);
        }

        $view = View::forge('user/products', [
            'products' => $products,
            'categories' => $categories,
            'current_category' => $category_id,
            'keyword' => $keyword,
            'current_page' => $page,
            'total_products' => $total_products,
            'limit' => $limit
        ]);

        return Response::forge(View::forge('layouts/user/base', [
            'main_content' => $view,
            'title' => 'Sản phẩm',
            'current_user' => $user_info,
            'categories' => $categories,
            'current_category' => $category_id,
            'keyword' => $keyword
        ]));
    }

    public function action_detail($id)
    {
        $current_user = Service_Auth::getCurrentUser();
        if (!$current_user) {
            Session::set_flash('error', 'Vui lòng đăng nhập');
            Response::redirect('auth/login');
            exit();
        }

        // Merge user info với profile
        $profile = Model_User::get_profile($current_user['id']);
        $user_info = array_merge($current_user, $profile);
        
        // Lấy thông tin sản phẩm
        $product = Service_Product::getById($id);
        if (!$product) {
            Session::set_flash('error', 'Không tìm thấy sản phẩm');
            Response::redirect('products');
            exit();
        }

        // Lấy danh sách categories
        $categories = Service_Category::getDropdown();

        // Lấy sản phẩm liên quan (cùng category)
        $related_products = [];
        if ($product['idCategory']) {
            $related_result = Service_Product::getByCategory($product['idCategory'], [
                'limit' => 4,
                'exclude_id' => $id
            ]);
            $related_products = $related_result['products'] ?? [];
        }

        $view = View::forge('user/product_detail', [
            'product' => $product,
            'related_products' => $related_products,
            'categories' => $categories
        ]);

        return Response::forge(View::forge('layouts/user/base', [
            'main_content' => $view,
            'title' => $product['name'],
            'current_user' => $user_info,
            'current_controller' => 'products',
            'current_action' => 'detail'
        ]));
    }
}
