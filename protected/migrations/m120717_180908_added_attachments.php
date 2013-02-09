<?php

class m120717_180908_added_attachments extends CDbMigration
{
	public function up()
	{
		$this->createTable("acl_action", array(
			"id" => "pk",
			"type" => "string",
			"content_id" => "int",
			"data" => "blob",
			"size" => "int",
			"name" => "string"
		), "");

		return true;
	}

	public function down()
	{
		$this->dropTable('acl_action');
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
