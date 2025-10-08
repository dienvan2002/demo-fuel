<?php

namespace Fuel\Migrations;

class Create_carts
{
	public function up()
	{
		\DBUtil::create_table('carts', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'idProduct' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'idUser' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));
		\DBUtil::add_foreign_key('carts', array(
			'key' => 'idProduct',
			'reference' => array(
				'table' => 'products',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'CASCADE'
		));
		\DBUtil::add_foreign_key('carts', array(
			'key' => 'idUser',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'CASCADE'
		));
	}

	public function down()
	{
		\DBUtil::drop_table('carts');
	}
}