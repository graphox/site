<?php
namespace Graphox\Modules\User;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @OGM\Entity
 * 
 * repositoryClass="\Graphox\Modules\User\Repository\User"
 */
class Email
{
    /**
     * @OGM\Auto
     */
    protected $id;
	
	/**
	 * Email of the user
	 * @OGM\Property
	 * @OGM\Index
	 */
	protected $email;

    /**
	 * Is this email primary?
     * @OGM\Property
     */
    protected $isPrimary;
	
	/**
	 * Is this email activated?
     * @OGM\Property
     */
    protected $isActivated;
	
	/**
	 * The activation key of the email?
     * @OGM\Property
	 * @OGM\Index
     */
    protected $activationKey;
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($name)
	{
		$this->email = $name;
	}
		
	public function setIsPrimary($value)
	{
		$this->isPrimary = $value;
	}
	
	public function getIsPrimary()
	{
		return $this->isPrimary;
	}
	
	public function isPrimary()
	{
		return $this->getIsPrimary();
	}

	public function setIsActivated($value)
	{
		$this->isActivated = $value;
	}
	
	public function getIsActivated()
	{
		return $this->isActivated;
	}
	
	public function isActivated()
	{
		return $this->getIsActivated();
	}

	public function setActivationKey($value)
	{
		$this->activationKey = $value;
	}
	
	public function getActivationKey()
	{
		return $this->activationKey;
	}
}