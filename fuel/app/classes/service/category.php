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

            return Model_Category::save_category($data['name']);
      }
}