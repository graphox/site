<?php

/**
 * An user executed action that is referenced from a timeline by an Action.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * An user executed action that is referenced from a timeline by an Action.
 * @package Graphox\Timeline
 * @OGM\Entity
 */
abstract class Verb implements IVerb, \Graphox\Content\IHaveContent
{
    /**
     * The id of the verb.
     * @OGM\Auto
     * @var int
     */
    protected $id;

    /**
     * The date the action was performed.
     * @OGM\Property(format="date")
     * @var DateTime
     */
	protected $createdDate;

	/**
     * Whether the verb is published.
     * @OGM\Property(format="boolean")
     * @OGM\Index
     * @var bool
     */
	protected $isPublished;

	/**
     * Whether the verp is "deleted"
     * @OGM\Property(format="boolean")
     * @OGM\Index
     * @var bool
     */
	protected $isDeleted;


	/**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

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
	public function getCreatedDate()
	{
		return $this->createdDate;
	}

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
	public function setIsPublished($published)
	{
		$this->isPublished = (bool) $published;
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

	/**
	 * {@inheritdoc}
	 */
	public function setIsDeleted($deleted)
	{
		$this->isDeleted = (bool) $deleted;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIsDeleted()
	{
		return $this->isDeleted;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isDeleted()
	{
		return $this->getIsDeleted();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isVisible()
	{
		return $this->isPublished() && !$this->isDeleted();
	}

}

