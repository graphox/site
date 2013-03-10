<?php

namespace Graphox\Timeline;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * The entity that contains the general information and links to thea ctual verb
 * @OGM\Entity
 */
class Action implements IAction
{
	/**
	 * @OGM\Auto
	 * @var int the id of the action
	 */
	protected $id;
	
	/**
	 * @OGM\ManyToOne
	 * @var Verb The actual action that is executed 
	 */
	protected $verb;
	
	/**
	 * @OGM\ManyToOne
	 * @var Action the previous executed action
	 */
	protected $next;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = (int)$id;
	}
	
	public function setVerb(Verb $verb)
	{
		$this->verb = $verb;
	}
	
	public function getVerb()
	{
		return $this->verb;
	}
	
	public function getNext()
	{
		return $this->next;
	}
	
	public function setNext(IAction $action)
	{
		$this->next = $action;
	}
}

