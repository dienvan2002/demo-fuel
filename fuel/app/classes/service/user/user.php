<?php
    class Service_User_User
        {
            public static function getPaginated($page = 1, $per_page = 3, $options = array())
        {
            // Cấu hình pagination
            $config = array(
                'pagination_url' => Uri::create('products/index'),
                'total_items'    => self::getCount(),
                'per_page'       => $per_page,
                'uri_segment'    => 'page',
            );

            // Tạo đối tượng Pagination
            $pagination = \Fuel\Core\Pagination::forge('user_pagination', $config);
            var_dump($pagination);
            exit();
            
            // Lấy danh sách users với pagination
            $users = self::getAll(array_merge($options, array(
                'limit' => $pagination->per_page,
                'offset' => $pagination->offset
            )));

            return array(
                'users' => $users,
                'pagination' => $pagination
            );
        }
        
        public static function getCount()
        {
            return DB::select(DB::expr('COUNT(*) as count'))
                ->from('products')
                ->execute()
                ->get('count');
        }
    }

?>