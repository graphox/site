<?php

/**
 * The entity on a timeline that points to a verb.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * The entity on a timeline that points to a verb.
 * @package Graphox\Timeline
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
    public function getId()
	{
		return $this->id;
	}

    /**
     * {@inheritdoc}
     */
    public function setId($id)
	{
		$this->id = (int)$id;
	}

    /**
     * {@inheritdoc}
     */
    public function setVerb(Verb $verb)
	{
		$this->verb = $verb;
	}

    /**
     * {@inheritdoc}
     */
    public function getVerb()
	{
		return $this->verb;
	}

    /**
     * {@inheritdoc}
     */
    public function getNext()
	{
		return $this->next;
	}

    /**
     * {@inheritdoc}
     */
    public function setNext(IAction $action)
	{
		$this->next = $action;
	}
}

