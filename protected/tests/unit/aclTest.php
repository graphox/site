<?php

class AclTest extends CTestCase
{
	public $fixtures=array(
		'aclFixtures' => 'Group'
	);
	
	public function setUp()
	{
	
		parent::setUp();
	}
	

	public function testModule()
	{
		$this->assertNotEquals(NULL, Yii::app()->accessControl);
	}
	
	public function testCreateGroup()
	{
		
	
		$acl = Yii::app()->accessControl;
		$group = $acl->getGroupByName('test', true);
		
		$this->assertEquals($group->name, 'test');		
	}
}
