<?php

namespace Fuel\Migrations;

class Create_products
{
	public function up()
	{
		\DBUtil::create_table('products', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'idCategory' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'name' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'price' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'img' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'description' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'visible' => array('null' => false, 'type' => 'boolean'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
		\DBUtil::add_foreign_key('products', array(
			'key' => 'idCategory',
			'reference' => array(
				'table' => 'categories',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'CASCADE'
		));
	}

	public function down()
	{
		\DBUtil::drop_table('products');
	}
}