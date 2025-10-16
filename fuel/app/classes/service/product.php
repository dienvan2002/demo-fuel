<?php

use Fuel\Core\Upload;
use Fuel\Core\Pagination;

class Service_Product
{
      public static function validate($data)
      {
            $errors = [];

            if (empty($data['name'])) {
                  $errors[] = 'Tên sản phẩm không được để trống';
            }

            if (!is_numeric($data['price']) || $data['price'] <= 0) {
                  $errors[] = 'Giá sản phẩm phải là số dương';
            }

            if (empty($data['idCategory'])) {
                  $errors[] = 'Vui lòng chọn danh mục';
            }

            return $errors;
      }

      public static function create($data)
      {
            // Set default values cho các field required
            $data['visible'] = isset($data['visible']) ? 1 : 0; // Checkbox: có tích = 1, không tích = 0
            $data['description'] = isset($data['description']) ? $data['description'] : '';

            $errors = self::validate($data);

            $file = Input::file('img');
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                  //  Kiểm tra kích thước file (ví dụ: tối đa 2MB)
                  $max_size = 2 * 1024 * 1024; // 2MB
                  if ($file['size'] > $max_size) {
                        $errors[] = 'Ảnh không được vượt quá 2MB';
                  }

                  //  Kiểm tra loại file
                  $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                  if (!in_array($file['type'], $allowed_types)) {
                        $errors[] = 'Ảnh phải là định dạng JPG, PNG hoặc GIF';
                  }

                  //  Nếu không có lỗi, tiến hành upload
                  if (empty($errors)) {
                        Upload::process([
                              'field' => 'img',
                              'path' => DOCROOT . 'uploads',
                              'randomize' => true,
                              'ext_whitelist' => ['jpg', 'jpeg', 'png', 'gif'],
                        ]);

                        if (Upload::is_valid()) {
                              Upload::save();
                              $files = Upload::get_files();


                              if (!empty($files) && isset($files[0]['saved_as'])) {
                                    $data['img'] = 'uploads/' . $files[0]['saved_as'];
                              } else {
                                    $errors[] = 'Không thể lưu ảnh';
                              }
                        } else {
                              $errors[] = 'Ảnh không hợp lệ hoặc không được chọn';
                        }
                  }
            } else {
                  $errors[] = 'Vui lòng chọn ảnh sản phẩm';
            }

            if (!empty($errors)) {
                  return ['success' => false, 'errors' => $errors];
            }

            return Model_Product::save_product($data);
      }


      public static function update($id, $data)
      {
            // Set default values
            $data['visible'] = isset($data['visible']) ? 1 : 0; // Checkbox: có tích = 1, không tích = 0
            $data['description'] = isset($data['description']) ? $data['description'] : '';

            $errors = self::validate($data);
            if (!empty($errors)) return ['success' => false, 'errors' => $errors];

            // Handle file upload if new image is provided
            $file = Input::file('img');
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                  // Validate file
                  $max_size = 2 * 1024 * 1024; // 2MB
                  if ($file['size'] > $max_size) {
                        $errors[] = 'Ảnh không được vượt quá 2MB';
                  }

                  $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                  if (!in_array($file['type'], $allowed_types)) {
                        $errors[] = 'Ảnh phải là định dạng JPG, PNG hoặc GIF';
                  }

                  if (empty($errors)) {
                        Upload::process([
                              'field' => 'img',
                              'path' => DOCROOT . 'uploads',
                              'randomize' => true,
                              'ext_whitelist' => ['jpg', 'jpeg', 'png', 'gif'],
                        ]);

                        if (Upload::is_valid()) {
                              Upload::save();
                              $files = Upload::get_files();

                              if (!empty($files) && isset($files[0]['saved_as'])) {
                                    $data['img'] = 'uploads/' . $files[0]['saved_as'];
                              }
                        }
                  }

                  if (!empty($errors)) {
                        return ['success' => false, 'errors' => $errors];
                  }
            }
            // If no new image, keep existing image (don't update img field)

            return Model_Product::update_product($id, $data);
      }

      public static function delete($id)
      {
            return Model_Product::delete_product($id);
      }


      public static function getById($id)
      {
            $product = Model_Product::find($id);
            return $product ? $product->to_array() : null;
      }

      /**
       * Lấy tất cả sản phẩm với options
       */
      public static function getAll($options = [])
      {
            $query = Model_Product::query();
            
            // Chỉ lấy sản phẩm hiển thị nếu không có option admin
            if (!isset($options['admin_mode']) || !$options['admin_mode']) {
                  $query->where('visible', 1);
            }

            // Apply filters
            if (isset($options['category_id']) && !empty($options['category_id'])) {
                  $query->where('idCategory', $options['category_id']);
            }

            if (isset($options['name']) && !empty($options['name'])) {
                  $query->where('name', 'LIKE', '%' . $options['name'] . '%');
            }

            // Order by
            $order_by = isset($options['order_by']) ? $options['order_by'] : 'created_at';
            $order_dir = isset($options['order_dir']) ? $options['order_dir'] : 'asc';
            $query->order_by($order_by, $order_dir);

            // Limit
            if (isset($options['limit'])) {
                  $query->limit($options['limit']);
            }

            // Offset
            if (isset($options['offset'])) {
                  $query->offset($options['offset']);
            }

            $products = $query->get();
            $products_array = [];
            foreach ($products as $product) {
                  $products_array[] = $product->to_array();
            }

            return [
                  'products' => $products_array,
                  'total' => count($products_array)
            ];
      }
      public static function search($keyword = null, $idCategory = null, $options = [])
      {
            $query = Model_Product::query()
                  ->where('visible', 1); // Chỉ lấy sản phẩm hiển thị

            if (!empty($keyword)) {
                  $query->where('name', 'like', '%' . $keyword . '%');
            }

            if (!empty($idCategory)) {
                  $query->where('idCategory', $idCategory);
            }

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

            $products = $query->get();
            $products_array = [];
            foreach ($products as $product) {
                  $products_array[] = $product->to_array();
            }

            return [
                  'products' => $products_array,
                  'total' => count($products_array)
            ];
      }

      /**
       * Lấy sản phẩm theo category với options
       */
      public static function getByCategory($category_id, $options = [])
      {
            $query = Model_Product::query()
                  ->where('idCategory', $category_id)
                  ->where('visible', 1); // Chỉ lấy sản phẩm hiển thị

            // Exclude product ID
            if (isset($options['exclude_id'])) {
                  $query->where('id', '!=', $options['exclude_id']);
            }

            // Order by
            $order_by = isset($options['order_by']) ? $options['order_by'] : 'created_at';
            $order_dir = isset($options['order_dir']) ? $options['order_dir'] : 'desc';
            $query->order_by($order_by, $order_dir);

            // Limit
            if (isset($options['limit'])) {
                  $query->limit($options['limit']);
            }

            $products = $query->get();
            $products_array = [];
            foreach ($products as $product) {
                  $products_array[] = $product->to_array();
            }

            return [
                  'products' => $products_array,
                  'total' => count($products_array)
            ];
      }

      /**
       * Lấy tổng số products
       */
      public static function getCount($admin_mode = false)
      {
            $query = Model_Product::query();
            
            if (!$admin_mode) {
                  $query->where('visible', 1);
            }
            
            return $query->count();
      }

      /**
       * Lấy danh sách products với pagination
       */
      public static function getPaginated($page = 1, $per_page = 10, $options = array())
      {
            // Cấu hình pagination
            $config = array(
                  'pagination_url' => Uri::create('admin/product/index'),
                  'total_items'    => self::getCount(isset($options['admin_mode']) ? $options['admin_mode'] : false),
                  'per_page'       => $per_page,
                  'uri_segment'    => 'page',
            );

            // Tạo đối tượng Pagination
            $pagination = \Fuel\Core\Pagination::forge('product_pagination', $config);
            
            // Lấy danh sách products với pagination
            $result = self::getAll(array_merge($options, array(
                  'limit' => $pagination->per_page,
                  'offset' => $pagination->offset
            )));
            

            return array(
                  'products' => $result['products'],
                  'pagination' => $pagination
            );
      }
}