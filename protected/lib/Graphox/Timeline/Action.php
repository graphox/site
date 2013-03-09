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
	
	/**
	 * {@inheritdoc}
	 */
	public function setCreatedDate(\DateTime $date)
	{
		$this->createdDate = $date;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCreatedDate()
	{
		return $date;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setIsPublished($value)
	{
		$this->isPublished = (bool)$value;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getIsPublished()
	{
		return $this->isPublished;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isPublished()
	{
		return $this->getIsPublished();
	}
	
	public function setVerb(Verb $verb)
	{
		$this->verb = $verb;
	}
	
	public function getVerb()
	{
		return $this->verb;
	}
}

