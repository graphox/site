<?php

namespace Graphox\Modules\User;

use HireVoice\Neo4j\Annotation as OGM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @OGM\Entity(repositoryClass="\Graphox\Modules\User\Repository\User")
 */
class User
{
    /**
     * @OGM\Auto
     */
    protected $id;

    /**
     * Username of the user
     * @OGM\Property
     * @OGM\Index
     */
    protected $userName;

    /**
     * Password of the user
     * @OGM\Property
     */
    protected $password;

    /**
     * The full name of the user
     * @OGM\Property
     * @OGM\Index
     */
    protected $fullName;

    /**
     * The registered email addresses of the user
     * @OGM\ManyToMany
     */
    protected $emails;

    /**
     * The avatar image of the user
     * @OGM\ManyToMany
     */
    protected $avatar;

    /**
     * Aditional profile metadata
     * @OGM\Property(format="json")
     */
    protected $profile;

    /**
     * Friends of the user
     * @OGM\ManyToMany
     */
    protected $friends;

    /**
     * The channels the user is following
     * @OGM\ManyToMany
     */
    protected $follows;

    /**
     * The timelines of the user
     * @OGM\ManyToMany
     */
    protected $timelines;

    public function __construct()
    {
        $this->follows = new ArrayCollection;
        $this->friends = new ArrayCollection;
        $this->avatar = new ArrayCollection;
        $this->emails = new ArrayCollection;
        $this->timelines= new ArrayCollection;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->userName;
    }

    public function setUsername($name)
    {
        $this->userName = $name;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmails()
    {
        return $this->emails;
    }

    public function addEmail(Email $email)
    {
        $this->emails->add($email);
    }

    public function setEmails(ArrayCollection $emails)
    {
        $this->emails = $emails;
    }

    public function getPrimaryEmail()
    {
        return ($v = $this->getEmails()->filter(function($mail)
                        {
                            return $mail->isPrimary();
                        })->first()) === false ? null : $v;
    }

    public function addFollow(User $user)
    {
        $this->follows->add($user);
    }

    public function removeFollow(User $user)
    {
        foreach ($this->follows as $key => $follower)
                if ($user->getId() == $follower->getId()) return $this->follows->remove($key);

        return false;
    }

    public function getTimelines()
    {
        return $this->timelines;
    }

    public function addTimeline(\Graphox\Timeline\Timeline $timeline)
    {
        $this->getTimelines()->add($timeline);
    }

    public function setTimelines(ArrayCollection $timelines)
    {
        $this->timelines = $timelines;
    }

    public function getPublicTimeline()
    {
        return ($v = $this->getTimelines()->filter(function($timeline)
                        {
                            return $timeline->isPublic();
                        })->first()) === false ? null : $v;
    }

    public function getPrimaryTimeline()
    {


        return ($v = $this->getTimelines()->filter(function($timeline)
                        {
                            return $timeline->isPrimary();
                        })->first()) === false ? null : $v;
    }

    public function getFollows()
    {
        return $this->follows;
    }

    public function isActivated()
    {
        return $this->getPrimaryEmail()->isActivated();
    }

    public function isAdmin()
    {
        return true;
    }

}

