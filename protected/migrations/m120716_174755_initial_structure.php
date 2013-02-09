<?php

class m120716_174755_initial_structure extends CDbMigration
{
	public function up()
	{
		$this->createTable("acl_action", array(
			"id" => "pk",
			"acl_object_id" => "int(11) NOT NULL",
			"name" => "varchar(50) NOT NULL",
			"default_value" => "tinyint(1) NOT NULL",
		), "");

		$this->createTable("acl_object", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
		), "");

		$this->createTable("activationkey", array(
			"id" => "pk",
			"hash" => "varchar(500) NOT NULL",
			"user_id" => "int(11) NOT NULL",
		), "");

		$this->createTable("content", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
			"content" => "text NOT NULL",
			"created_date" => "datetime NOT NULL",
			"updated_date" => "datetime",
			"creator_id" => "int(11) NOT NULL",
			"updater_id" => "int(11)",
			"acl_object_id" => "int(11) NOT NULL",
			"type_id" => "int(11) NOT NULL",
			"language_id" => "int(11) NOT NULL",
			"can_comment" => "tinyint(1) NOT NULL",
			"markup_id" => "int(11) NOT NULL",
			"published" => "int(11) NOT NULL",
			"widgets_enabled" => "tinyint(1) NOT NULL",
			"parent_id" => "int(11)",
			"html" => "text NOT NULL",
		), "");

		$this->createTable("content_type", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
			"class" => "varchar(50) NOT NULL",
		), "");

		$this->createTable("group", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
			"acl_object_id" => "int(11) NOT NULL",
		), "");

		$this->createTable("group_action", array(
			"id" => "pk",
			"group_id" => "int(11) NOT NULL",
			"action_id" => "int(11) NOT NULL",
			"order_id" => "int(11) NOT NULL",
			"value" => "int(11) NOT NULL",
		), "");

		$this->createTable("group_user", array(
			"id" => "pk",
			"group_id" => "int(11) NOT NULL",
			"user_id" => "int(11) NOT NULL",
		), "");

		$this->createTable("language", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
		), "");

		$this->createTable("markup", array(
			"id" => "pk",
			"name" => "varchar(50) NOT NULL",
			"acl_object_id" => "int(11) NOT NULL",
			"class" => "varchar(50) NOT NULL",
			"settings" => "text NOT NULL",
		), "");

		$this->createTable("user", array(
			"id" => "pk",
			"username" => "varchar(10) NOT NULL",
			"email" => "varchar(50) NOT NULL",
			"display_group_id" => "int(11)",
			"password" => "text NOT NULL",
			"ingame_password" => "text NOT NULL",
			"status" => "enum('banned','active','pending','admin_pending','oauth') NOT NULL",
		), "");

		echo 'The following (possible) errors are caused by the foreign keus, you may ignore them or port this stuff to yii.';

		/**
		 * TODO: compatibility
		 */

		$this->execute(<<<SQL
			ALTER TABLE `acl_action`
			  ADD CONSTRAINT `acl_action_ibfk_1` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

			ALTER TABLE `activationkey`
			  ADD CONSTRAINT `activationkey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

			ALTER TABLE `content`
			  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`updater_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
			  ADD CONSTRAINT `content_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`),
			  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `content_type` (`id`),
			  ADD CONSTRAINT `content_ibfk_4` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
			  ADD CONSTRAINT `content_ibfk_5` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
			  ADD CONSTRAINT `content_ibfk_6` FOREIGN KEY (`markup_id`) REFERENCES `markup` (`id`);

			ALTER TABLE `group`
			  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

			ALTER TABLE `group_action`
			  ADD CONSTRAINT `group_action_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `acl_action` (`id`),
			  ADD CONSTRAINT `group_action_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

			ALTER TABLE `group_user`
			  ADD CONSTRAINT `group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
			  ADD CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

			ALTER TABLE `markup`
			  ADD CONSTRAINT `markup_ibfk_2` FOREIGN KEY (`acl_object_id`) REFERENCES `acl_object` (`id`);

			ALTER TABLE `user`
			  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`display_group_id`) REFERENCES `group` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
SQL
);
		return true;
	}

	public function down()
	{
		echo "m120716_174755_initial_structure does not support migration down.\n";
		return false;
	}
}
