<?php

use Fuel\Core\Upload;

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
            $data['visible'] = isset($data['visible']) ? (int)$data['visible'] : 1;
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
            $errors = self::validate($data);
            if (!empty($errors)) return ['success' => false, 'errors' => $errors];

            return Model_Product::update_product($id, $data);
      }

      public static function delete($id)
      {
            return Model_Product::delete_product($id);
      }

      public static function get_all()
      {
            return Model_Product::find('all');
      }

      public static function get_by_id($id)
      {
            return Model_Product::find($id);
      }
      public static function search($keyword = null, $idCategory = null)
      {
            $query = Model_Product::query();

            if (!empty($keyword)) {
                  $query->where('name', 'like', '%' . $keyword . '%');
            }

            if (!empty($idCategory)) {
                  $query->where('idCategory', $idCategory);
            }

            return $query->get();
      }
}