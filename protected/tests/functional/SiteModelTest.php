<?php

/**
 * Test case for Site model
 */

class SiteModelTest extends CTestCase
{

	/**
	 * @var Site
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		Yii::import("site.models.Site");
		$this->object = new Site;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		
	}

	/**
	 * @covers Site::getName
	 */
	public function testGetName()
	{
		$this->assertTrue(is_string($this->object->getName()));
		$this->assertTrue(is_string($this->object->name));
		$this->assertEquals(		$this->object->name, $this->object->getName());
	}

	/**
	 * @covers Site::getTitle
	 */
	public function testGetTitle()
	{
		$this->assertTrue(is_string($this->object->getTitle()));
		$this->assertTrue(is_string($this->object->title));
		$this->assertEquals(		$this->object->title, $this->object->getTitle());
	}
	
	/**
	 * @covers Site::getDescription
	 */
	public function testGetDescription()
	{
		$this->assertTrue(is_string($this->object->getDescription()));
		$this->assertTrue(is_string($this->object->description));
		$this->assertEquals($this->object->description, $this->object->getDescription());
	}

	/**
	 * @covers Site::attributeNames
	 */
	public function testAttributeNames()
	{
		$this->assertTrue(is_array($this->object->attributeNames()));
	}
	
	/**
	 * @covers Site::init
	 */
	public function testInit()
	{
		$this->assertTrue(true);
	}

}
