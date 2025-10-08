<?php
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

            // Gọi Model để xử lý business logic và return kết quả
            return Model_Category::create_category($data);
      }
}
