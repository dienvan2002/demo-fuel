<?php

/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 */

	'_root_' => 'auth/login',  // Redirect về login

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 */

	'_404_' => 'welcome/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Authentication routes
	 * -------------------------------------------------------------------------
	 */
	'auth/login' => 'auth/login',
	'auth/logout' => 'auth/logout',
	'auth/register' => 'auth/register',

	/**
	 * -------------------------------------------------------------------------
	 *  Admin routes (cần đăng nhập và quyền admin)
	 * -------------------------------------------------------------------------
	 */
	'admin/home' => array('admin/home', 'name' => 'admin_home'),
	'admin/category/create' => 'admin/category/create',
	'admin/category/search' => 'admin/category/search',
	'admin/category/edit/(:segment)' => 'admin/category/edit/$1',
	'admin/category/delete/(:segment)' => 'admin/category/delete/$1',
	'admin/category/dropdown' => 'admin/category/dropdown',
	'admin/product/create' => 'admin/product/create',
	'admin/product/edit/(:segment)' => 'admin/product/edit/$1',
	'admin/product/delete/(:segment)' => 'admin/product/delete/$1',

	/**
	 * -------------------------------------------------------------------------
	 *  User routes (cần đăng nhập)
	 * -------------------------------------------------------------------------
	 */
	'user/home' => array('user/home', 'name' => 'user_home'),
	'user/products' => 'user/products',
	'user/cart' => 'user/cart',

	/**
	 * -------------------------------------------------------------------------
	 *  Public routes (không cần đăng nhập)
	 * -------------------------------------------------------------------------
	 */
	'products' => 'welcome/products',
	'categories' => 'welcome/categories',

	/**
	 * -------------------------------------------------------------------------
	 *  Example for Presenter
	 * -------------------------------------------------------------------------
	 */

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);