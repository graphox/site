<?php

namespace Graphox\Timeline;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @OGM\Entity
 */
abstract class Verb implements IVerb, \Graphox\Content\IHaveContent
{

	/**
	 * @OGM\Property(format="date")
	 * @var DateTime the date the action was performed
	 */
	protected $createdDate;

	/**
	 * @OGM\Property(format="boolean")
	 * @OGM\Index
	 * @var bool whether the verb is published
	 */
	protected $isPublished;

	/**
	 * @OGM\Property(format="boolean")
	 * @OGM\Index
	 * @var bool whether the verp is "deleted"
	 */
	protected $isDeleted;

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

