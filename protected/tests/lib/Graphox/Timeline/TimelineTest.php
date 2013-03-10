<?php

namespace Graphox\Timeline;

class TimelineTest extends \PHPUnit_Framework_TestCase
{
	public function ids()
	{
		return array(
			array(0),
			array(1),
			array(3),
			array(5),
			array(10),
			array(100),
			array(99),
		);
	}
	
	/**
	 * @dataProvider ids
	 * @covers \Graphox\Timeline\Timeline::setId
	 * @covers \Graphox\Timeline\Timeline::getId
	 */
	public function testId($id)
	{
		$timeline = new Timeline;
		$timeline->setId($id);
		$this->assertEquals($id, $timeline->getId());
	}
	
	/**
	 * @covers \Graphox\Timeline\Timeline::setFirst
	 * @covers \Graphox\Timeline\Timeline::getFirst
	 * @covers \Graphox\Timeline\Timeline::getLast
	 * @covers \Graphox\Timeline\Timeline::setLast
	 * @covers \Graphox\Timeline\Timeline::push
	 * @covers \Graphox\Timeline\Timeline::append
	 */
	public function testTimeline()
	{
		$timeline = new Timeline;
		$action = new Action;
		$action->setId(0);
		
		$timeline->setFirst($action);
		$timeline->setLast($action);
		
		$this->assertEquals($timeline->getFirst()->getId(), $timeline->getLast()->getId());
		$this->assertEquals($timeline->getFirst()->getId(), $action->getId());
		
		$action2 = new Action;
		$action2->setId(1);
		
		$timeline->push($action2);
		
		$this->assertEquals($timeline->getLast()->getId(), $action2->getId());
		$this->assertEquals($timeline->getFirst()->getId(), $action->getId());
		$this->assertEquals($timeline->getLast()->getNext()->getId(), $action->getId());
		
		$action3 = new Action;
		$action3->setId(3);
		
		$timeline->append($action3);
		$this->assertEquals($timeline->getLast()->getId(), $action2->getId());
		$this->assertEquals($timeline->getFirst()->getId(), $action3->getId());
		$this->assertEquals($timeline->getLast()->getNext()->getId(), $action->getId());
		$this->assertEquals($timeline->getLast()->getNext()->getNext()->getId(), $action3->getId());
		
	}	
}

