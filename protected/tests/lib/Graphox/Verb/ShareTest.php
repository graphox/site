<?php

namespace Graphox\Verb;

class ShareTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider content
	 * @covers Graphox\Verb\Share::getContent
	 * @covers Graphox\Verb\Share::setContent
	 */
	function testGetSetContent($content)
	{
		$share = new Share;
		$share->setContent($content);
		$this->assertEquals($share->getContent(), $content);
	}

	/**
	 * @dataProvider content
	 * @covers Graphox\Verb\Share::getSource
	 * @covers Graphox\Verb\Share::setSource
	 */
	function testGetSetSource($content)
	{
		$share = new Share;
		$share->setSource($content);
		$this->assertEquals($share->getSource(), $content);
	}

	function content()
	{
		return array(
			array('Hello world!'),
			array('Test\'n'),
			array('Htm&01;l')
		);
	}

	/**
	 * @dataProvider dateTime
	 * @covers Graphox\Timeline\Verb::getCreatedDate
	 * @covers Graphox\Timeline\Verb::setCreatedDate
	 */
	function testGetSetCreatedDate($date)
	{
		$share = new Share;
		$share->setCreatedDate($date);
		$this->assertEquals($share->getCreatedDate(), $date);
	}

	function dateTime()
	{
		return array(
			array(new \DateTime()),
			array(new \DateTime('15810726')),
			array(new \DateTime('19780417')),
			array(new \DateTime('18140517')),
			array(new \DateTime('2008/06/30')),
			array(new \DateTime('1978/12/22')),
			array(new \DateTime('08-06-30')),
			array(new \DateTime('78-12-22')),
			array(new \DateTime('-0002-07-26')),
			array(new \DateTime('+1978-04-17')),
			array(new \DateTime('1814-05-17')),
		);
	}

	/**
	 * @dataProvider published
	 * @covers Graphox\Timeline\Verb::getIsPublished
	 * @covers Graphox\Timeline\Verb::setIsPublished
	 * @covers Graphox\Timeline\Verb::isPublished
	 */
	function testIsPublished($published, $expected)
	{
		$share = new Share;
		$share->setIsPublished($published);
		$this->assertEquals($share->isPublished(), $expected);
		$this->assertEquals($share->getIsPublished(), $expected);
	}

	function published()
	{
		return array(
			array(true, true),
			array(false, false),
			array(1, true),
			array(0, false),
		);
	}

	/**
	 * @dataProvider deleted
	 * @covers Graphox\Timeline\Verb::getIsDeleted
	 * @covers Graphox\Timeline\Verb::setIsDeleted
	 * @covers Graphox\Timeline\Verb::isDeleted
	 */
	function testIsDeleted($deleted, $expected)
	{
		$share = new Share;
		$share->setIsDeleted($deleted);
		$this->assertEquals($share->isDeleted(), $expected);
		$this->assertEquals($share->getIsDeleted(), $expected);
	}

	function deleted()
	{
		return array(
			array(true, true),
			array(false, false),
			array(1, true),
			array(0, false),
		);
	}

	/**
	 * @dataProvider visible
	 * @covers Graphox\Timeline\Verb::isVisible
	 */
	function testIsVisible($deleted, $published, $expected)
	{
		$share = new Share;
		$share->setIsDeleted($deleted);
		$share->setIsPublished($published);

		$this->assertEquals($share->isDeleted(), $deleted);
		$this->assertEquals($share->isPublished(), $published);
		$this->assertEquals($share->isVisible(), $expected);
	}

	function visible()
	{
		return array(
			array(false, true, true),
			array(true, true, false),
			array(true, false, false),
			array(false, false, false),
		);
	}

}

