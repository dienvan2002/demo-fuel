<?php
class Controller_Admin_Product extends Controller
{
      public function action_create()
      {
            $categories = Model_Category::get_dropdown();
            $success_message = null;
            $error_messages = null;
            if (Input::method() === 'POST') {
                  $result = Service_Product::create(Input::post());

                  if ($result['success']) {
                        $success_message = $result['message'];
                        Response::redirect('admin/product/create');
                  } else {
                        $error_messages = $result['errors'];
                  }
            }

            $main_content = View::forge('admin/product/create', [
                  'categories' => $categories,
                  'success_message' => $success_message,
                  'error_messages' => $error_messages
            ]);
            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/create_product.css'
            ]));
      }

      public function action_index()
      {
            $products = Service_Product::get_all();
            $main_content = View::forge('admin/product/index', ['products' => $products]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content
            ]));
      }

      public function action_edit($id)
      {
            $product = Service_Product::get_by_id($id);
            $categories = Model_Category::get_dropdown();

            if (!$product) throw new HttpNotFoundException();

            if (Input::method() === 'POST') {
                  $result = Service_Product::update($id, Input::post());

                  if ($result['success']) {
                        Session::set_flash('success', 'Cập nhật sản phẩm thành công');
                        Response::redirect('admin/product/edit/' . $id);
                  } else {
                        Session::set_flash('errors', $result['errors']);
                  }
            }

            $main_content = View::forge('admin/product/edit', [
                  'product' => $product,
                  'categories' => $categories
            ]);

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content
            ]));
      }

      public function action_delete($id = null)
      {
            if ($id === null || !is_numeric($id)) {
                  Session::set_flash('error', 'ID sản phẩm không hợp lệ.');
                  Response::redirect('admin/product/search');
            }

            $deleted = Service_Product::delete($id);

            if ($deleted) {
                  Session::set_flash('success', 'Xóa sản phẩm thành công.');
            } else {
                  Session::set_flash('error', 'Không thể xóa sản phẩm.');
            }

            Response::redirect('admin/product/search');
      }

      public function action_search()
      {
            $keyword = Input::get('keyword');
            $idCategory = Input::get('idCategory');

            $data['products'] = Service_Product::search($keyword, $idCategory);
            $data['categories'] = Model_Category::get_dropdown();

            $main_content = View::forge('admin/product/search', $data);
            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/search.css'
            ]));
      }
}