<?php

namespace Graphox\Timeline;

class Timeline
{
	/**
	 * @OGM\Auto
	 * @var int the id of the action
	 */
	protected $id;
	
	/**
	 * @OGM\ManyToOne
	 * @var Action The first action executed
	 */
	protected $last;
	
	/**
	 * @OGM\ManyToOne
	 * @var Action the last action executed
	 */
	protected $first;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = (int)$id;
	}
	
	public function setLast(IAction $action)
	{
		$this->last = $action;
	}
	
	public function getLast()
	{
		return $this->last;
	}
	
	public function getFirst()
	{
		return $this->first;
	}
	
	public function setFirst(IAction $action)
	{
		$this->first = $action;
	}
	
	public function push(IAction $action)
	{
		$current = $this->getLast();
		$action->setNext($current);
		$this->setLast($action);
	}
	
	public function append(IAction $action)
	{
		$current = $this->getFirst();
		$current->setNext($action);
		$this->setFirst($action);
	}
}

