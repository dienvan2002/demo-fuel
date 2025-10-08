<?php

use \Orm\Model;

class Model_Category extends Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"name" => array(
			"label" => "Name",
			"data_type" => "varchar",
		),
		"created_at" => array(
			"label" => "Created at",
			"data_type" => "int",
		),
		"updated_at" => array(
			"label" => "Updated at",
			"data_type" => "int",
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'property' => 'created_at',
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'property' => 'updated_at',
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'categories';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'products' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Product',
			'key_to' => 'idCategory',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array();

	/**
	 * Validation rules for category
	 */
	private static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Tên danh mục', 'required|max_length[255]|min_length[2]');
		return $val;
	}

	/**
	 * Create new category with validation
	 * Fat Model: Business logic trong model
	 */
	public static function create_category($data)
	{
		// Validate input data
		$val = static::validate('create_category');
		if (!$val->run($data)) {
			return array(
				'success' => false,
				'errors' => $val->error()
			);
		}

		// Check if category name already exists
		$existing = static::query()
			->where('name', 'LIKE', trim($data['name']))
			->get_one();

		if ($existing) {
			return array(
				'success' => false,
				'errors' => array('name' => 'Tên danh mục đã tồn tại')
			);
		}

		try {
			// Create new category
			$category = static::forge(array(
				'name' => trim($data['name'])
			));

			if ($category->save()) {
				return array(
					'success' => true,
					'data' => $category,
					'message' => 'Thêm danh mục thành công!'
				);
			} else {
				return array(
					'success' => false,
					'errors' => array('general' => 'Không thể lưu danh mục')
				);
			}
		} catch (Exception $e) {
			return array(
				'success' => false,
				'errors' => array('general' => 'Lỗi hệ thống: ' . $e->getMessage())
			);
		}
	}

	/**
	 * Update category with validation
	 */
	public function update_category($data)
	{
		$val = static::validate('update_category');
		if (!$val->run($data)) {
			return array(
				'success' => false,
				'errors' => $val->error()
			);
		}

		// Check if name exists (exclude current category)
		$existing = static::query()
			->where('name', 'LIKE', trim($data['name']))
			->where('id', '!=', $this->id)
			->get_one();

		if ($existing) {
			return array(
				'success' => false,
				'errors' => array('name' => 'Tên danh mục đã tồn tại')
			);
		}

		try {
			$this->name = trim($data['name']);

			if ($this->save()) {
				return array(
					'success' => true,
					'data' => $this,
					'message' => 'Cập nhật danh mục thành công!'
				);
			} else {
				return array(
					'success' => false,
					'errors' => array('general' => 'Không thể cập nhật danh mục')
				);
			}
		} catch (Exception $e) {
			return array(
				'success' => false,
				'errors' => array('general' => 'Lỗi hệ thống: ' . $e->getMessage())
			);
		}
	}

	/**
	 * Delete category with validation
	 */
	public function delete_category()
	{
		// Check if category has products
		$product_count = count($this->products);

		if ($product_count > 0) {
			return array(
				'success' => false,
				'errors' => array('general' => "Không thể xóa danh mục có {$product_count} sản phẩm")
			);
		}

		try {
			if ($this->delete()) {
				return array(
					'success' => true,
					'message' => 'Xóa danh mục thành công!'
				);
			} else {
				return array(
					'success' => false,
					'errors' => array('general' => 'Không thể xóa danh mục')
				);
			}
		} catch (Exception $e) {
			return array(
				'success' => false,
				'errors' => array('general' => 'Lỗi hệ thống: ' . $e->getMessage())
			);
		}
	}

	/**
	 * Search categories with filters
	 */
	public static function search_categories($filters = array())
	{
		$query = static::query();

		// Apply name filter
		if (!empty($filters['name'])) {
			$query->where('name', 'LIKE', '%' . $filters['name'] . '%');
		}

		// Apply date filter
		if (!empty($filters['date_from'])) {
			$query->where('created_at', '>=', strtotime($filters['date_from']));
		}

		if (!empty($filters['date_to'])) {
			$query->where('created_at', '<=', strtotime($filters['date_to'] . ' 23:59:59'));
		}

		// Order by
		$order_by = isset($filters['order_by']) ? $filters['order_by'] : 'created_at';
		$order_dir = isset($filters['order_dir']) ? $filters['order_dir'] : 'desc';
		$query->order_by($order_by, $order_dir);

		// Pagination
		$limit = isset($filters['limit']) ? (int)$filters['limit'] : 20;
		$offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;

		$query->limit($limit)->offset($offset);

		return $query->get();
	}

	/**
	 * Get category with product count
	 */
	public function get_with_product_count()
	{
		$this->product_count = count($this->products);
		return $this;
	}

	/**
	 * Get all categories for dropdown
	 */
	public static function get_for_dropdown()
	{
		$categories = static::query()->order_by('name', 'asc')->get();
		$options = array();

		foreach ($categories as $category) {
			$options[$category->id] = $category->name;
		}

		return $options;
	}
	public static function get_dropdown()
	{
		$products = self::find('all');
		$result = [];

		foreach ($products as $p) {
			$result[$p->id] = $p->name;
		}

		return $result;
	}
}