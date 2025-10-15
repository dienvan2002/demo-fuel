<?php
use Fuel\Core\Pagination;

class Service_Category
{
      public static function create($data)
      {
            $errors = [];

            if (empty($data['name'])) {
                  $errors[] = 'Tên danh mục không được để trống';
            }

            if (!empty($errors)) {
                  return ['success' => false, 'errors' => $errors];
            }

            // Validate input data
            $val = Validation::forge('create_category');
            $val->add_field('name', 'Tên danh mục', 'required|max_length[255]|min_length[2]');
            
            if (!$val->run($data)) {
                  return array(
                        'success' => false,
                        'errors' => $val->error()
                  );
            }

            // Check for duplicate name
            $existing = Model_Category::query()
                  ->where('name', $data['name'])
                  ->get_one();

            if ($existing) {
                  return array(
                        'success' => false,
                        'errors' => array('Tên danh mục đã tồn tại')
                  );
            }

            // Create new category
            $category = Model_Category::forge(array(
                  'name' => $data['name']
            ));

            if ($category->save()) {
                  return array(
                        'success' => true,
                        'message' => 'Tạo danh mục thành công',
                        'category' => $category
                  );
            } else {
                  return array(
                        'success' => false,
                        'errors' => array('Lỗi khi tạo danh mục')
                  );
            }
      }

      public static function update($id, $data)
      {
            $errors = [];

            if (empty($data['name'])) {
                  $errors[] = 'Tên danh mục không được để trống';
            }

            if (!empty($errors)) {
                  return ['success' => false, 'errors' => $errors];
            }

            // Validate input data
            $val = Validation::forge('update_category');
            $val->add_field('name', 'Tên danh mục', 'required|max_length[255]|min_length[2]');
            
            if (!$val->run($data)) {
                  return array(
                        'success' => false,
                        'errors' => $val->error()
                  );
            }

            // Find existing category
            $category = Model_Category::find($id);
            if (!$category) {
                  return array(
                        'success' => false,
                        'errors' => array('Không tìm thấy danh mục')
                  );
            }

            // Check for duplicate name (excluding current category)
            $existing = Model_Category::query()
                  ->where('name', $data['name'])
                  ->where('id', '!=', $id)
                  ->get_one();

            if ($existing) {
                  return array(
                        'success' => false,
                        'errors' => array('Tên danh mục đã tồn tại')
                  );
            }

            // Update category
            $category->name = $data['name'];
            
            if ($category->save()) {
                  return array(
                        'success' => true,
                        'message' => 'Cập nhật danh mục thành công',
                        'category' => $category
                  );
            } else {
                  return array(
                        'success' => false,
                        'errors' => array('Lỗi khi cập nhật danh mục')
                  );
            }
      }

      public static function delete($id)
      {
            // Find existing category
            $category = Model_Category::find($id);
            if (!$category) {
                  return array(
                        'success' => false,
                        'message' => 'Không tìm thấy danh mục'
                  );
            }

            // Check if category has products
            $product_count = count($category->products);
            if ($product_count > 0) {
                  return array(
                        'success' => false,
                        'message' => "Không thể xóa danh mục này vì có {$product_count} sản phẩm đang sử dụng"
                  );
            }

            // Delete category
            if ($category->delete()) {
                  return array(
                        'success' => true,
                        'message' => 'Xóa danh mục thành công'
                  );
            } else {
                  return array(
                        'success' => false,
                        'message' => 'Lỗi khi xóa danh mục'
                  );
            }
      }

      public static function getAll($filters = array())
      {
            $query = Model_Category::query();

            // Apply name filter
            if (!empty($filters['name'])) {
                  $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
            }

            // Order by
            $order_by = isset($filters['order_by']) ? $filters['order_by'] : 'created_at';
            $order_dir = isset($filters['order_dir']) ? $filters['order_dir'] : 'desc';
            $query->order_by($order_by, $order_dir);

            // Apply limit and offset for pagination
            if (isset($filters['limit'])) {
                  $query->limit($filters['limit']);
            }
            
            if (isset($filters['offset'])) {
                  $query->offset($filters['offset']);
            }

            return $query->get();
      }

      public static function getById($id)
      {
            $category = Model_Category::find($id);
            return $category ? $category->to_array() : null;
      }

      public static function getDropdown()
      {
            $categories = Model_Category::find('all', array(
                  'order_by' => array('name' => 'asc')
            ));
            
            $options = array();
            foreach ($categories as $category) {
                  $options[$category->id] = $category->name;
            }

            return $options;
      }
      /**
       * Lấy số lượng sản phẩm trong category
       */
      public static function getProductCount($category_id)
      {
            return Model_Product::query()
                  ->where('idCategory', $category_id)
                  ->count();
      }

      /**
       * Lấy tổng số categories
       */
      public static function getCount()
      {
            return Model_Category::count();
      }

      /**
       * Lấy danh sách categories với pagination
       */
      public static function getPaginated($page = 1, $per_page = 10, $filters = array())
      {
            // Cấu hình pagination
            $config = array(
                  'pagination_url' => Uri::create('admin/category/index'),
                  'total_items'    => self::getCount(),
                  'per_page'       => $per_page,
                  'uri_segment'    => 'page',
            );

            // Tạo đối tượng Pagination
            $pagination = \Fuel\Core\Pagination::forge('category_pagination', $config);
            
            // Lấy danh sách categories với pagination
            $categories = self::getAll(array_merge($filters, array(
                  'limit' => $pagination->per_page,
                  'offset' => $pagination->offset
            )));

            return array(
                  'categories' => $categories,
                  'pagination' => $pagination
            );
      }

}