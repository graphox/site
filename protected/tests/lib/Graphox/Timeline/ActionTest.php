<?php

namespace Graphox\Timeline;

class ActionTest extends \PHPUnit_Framework_TestCase
{

	public function action()
	{
		return array(
			array(array('id' => 0)),
			array(array('id' => 1)),
			array(array('id' => 2)),
			array(array('id' => 3)),
			array(array('id' => 10)),
			array(array('id' => 11)),
			array(array('id' => 889)),
		);
	}
	/**
	 * @covers Graphox\Timeline\Action::getId
	 * @covers Graphox\Timeline\Action::setId
	 * @dataProvider action
	 */
	public function testId($data)
	{
		$action = new Action;
		$action->setId($data['id']);
		$this->assertEquals($action->getId(), $data['id']);
	}

	/**
	 * @covers Graphox\Timeline\Action::setVerb
	 * @covers Graphox\Timeline\Action::getVerb
	 */
	public function testVerb()
	{
		$action = new Action;
		$verb = new \Graphox\Verb\Share;
		
		$action->setVerb($verb);
		$this->assertEquals($verb, $action->getVerb());
	}

	/**
	 * @depends testId
	 * @depends testVerb
	 * @covers Graphox\Timeline\Action::getNext
	 * @covers Graphox\Timeline\Action::setNext
	 */
	public function testNext()
	{
		$data = $this->action();
		
		$start = new Action;
		$last = $start;
		
		foreach($data as $d)
		{
			$current = new Action;
			$current->setId($d[0]['id']);
			$last->setNext($current);
			$last = $current;
		}
		
		reset($data);
		while($n = $start->getNext())
		{
			$start = $n;
			$current = current($data);
			$this->assertEquals($current[0]['id'], $start->getId());
			next($data);
		}
	}

}