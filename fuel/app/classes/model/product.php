<?php

class Model_Product extends \Orm\Model
{
	protected static $_properties = array(
		"id" => array(
			"label" => "Id",
			"data_type" => "int",
		),
		"idCategory" => array(
			"label" => "IdCategory",
			"data_type" => "int",
		),
		"name" => array(
			"label" => "Name",
			"data_type" => "varchar",
		),
		"price" => array(
			"label" => "Price",
			"data_type" => "int",
		),
		"img" => array(
			"label" => "Img",
			"data_type" => "varchar",
		),
		"description" => array(
			"label" => "Description",
			"data_type" => "varchar",
		),
		"visible" => array(
			"label" => "Visible",
			"data_type" => "boolean",
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

	protected static $_table_name = 'products';

	protected static $_primary_key = array('id');

	protected static $_has_many = array(
		'carts' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Cart',
			'key_to' => 'idProduct',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);

	protected static $_many_many = array();

	protected static $_has_one = array();

	protected static $_belongs_to = array();


	public static function save_product($data)
	{
		$product = self::forge([
			'idCategory'  => $data['idCategory'],
			'name'        => $data['name'],
			'price'       => $data['price'],
			'img'         => isset($data['img']) ? $data['img'] : null,
			'description' => isset($data['description']) ? $data['description'] : '',
			'visible'     => isset($data['visible']) ? (int)$data['visible'] : 1,
		]);

		$product->save();
		return ['success' => true, 'message' => 'Thêm sản phẩm thành công!'];
	}

	public static function update_product($id, $data)
	{
		$product = self::find($id);
		if (!$product) return ['success' => false, 'errors' => ['Sản phẩm không tồn tại']];

		$product->set($data);
		$product->save();
		return ['success' => true];
	}

	public static function delete_product($id)
	{
		$product = self::find($id);
		if ($product) $product->delete();
		return ['success' => true];
	}
	
}