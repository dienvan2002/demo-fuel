<?php

/**
 * SimpleAuth Configuration cho dự án demo-fuel
 */
return array(

	/**
	 * DB connection, sử dụng default
	 */
	'db_connection' => null,

	/**
	 * DB write connection
	 */
	'db_write_connection' => null,

	/**
	 * Tên bảng users
	 */
	'table_name' => 'users',

	/**
	 * Các cột trong bảng users (chỉ dùng cột chuẩn của SimpleAuth)
	 * Set array('*') để select tất cả
	 */
	'table_columns' => array('*'),

	/**
	 * Cho phép guest login
	 */
	'guest_login' => true,

	/**
	 * Cho phép đăng nhập nhiều nơi cùng lúc
	 */
	'multiple_logins' => false,

	/**
	 * Remember-me functionality
	 */
	'remember_me' => array(
		'enabled' => true,
		'cookie_name' => 'rmcookie',
		'expiration' => 86400 * 31, // 31 ngày
	),

	/**
	 * Định nghĩa Groups với phân quyền
	 */
	'groups' => array(
		/**
		 * Group ID => array(name => tên nhóm, roles => array các quyền)
		 */
		0    => array('name' => 'Guests', 'roles' => array()),
		1    => array('name' => 'Users', 'roles' => array('user')),
		100  => array('name' => 'Administrators', 'roles' => array('user', 'admin')),
	),

	/**
	 * Định nghĩa Roles với quyền cụ thể
	 */
	'roles' => array(
		/**
		 * Role 'user' - quyền cơ bản
		 */
		'user' => array(
			'products' => array('read'),
			'categories' => array('read'),
			'cart' => array('create', 'read', 'update', 'delete'),
		),

		/**
		 * Role 'admin' - quyền quản trị
		 */
		'admin' => array(
			'products' => array('create', 'read', 'update', 'delete'),
			'categories' => array('create', 'read', 'update', 'delete'),
			'users' => array('create', 'read', 'update', 'delete'),
			'admin' => array('access'),
		),

		/**
		 * Quyền đọc cho tất cả (kể cả guest)
		 */
		'#' => array(
			'website' => array('read'),
			'products' => array('read'),
			'categories' => array('read'),
		),
	),

	/**
	 * Salt cho login hash - THAY ĐỔI TRONG PRODUCTION!
	 * PHẢI KHỚP VỚI salt trong auth.php
	 */
	'login_hash_salt' => 'put_some_salt_in_here',

	/**
	 * Tên field trong POST cho username
	 */
	'username_post_key' => 'username',

	/**
	 * Tên field trong POST cho password
	 */
	'password_post_key' => 'password',
);
