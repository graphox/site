<?php

/**
 * A timeline of an user.
 * Represents the actions an user has executed.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

use HireVoice\Neo4j\Annotation as OGM;

/**
 * A timeline of an user.
 * Represents the actions an user has executed.
 * @package Graphox\Timeline
 * @OGM\Entity(repositoryClass="\Graphox\Timeline\TimelineRepository")
 */
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

    /**
     * Wether this timeline is the user's public timeline
     * @var bool
     * @OGM\Index
     * @OGM\Property
     */
    protected $isPublic;

    /**
     * Wether this timeline is the user's private timeline.
     * @var bool
     * @OGM\Index
     * @OGM\Property
     */
    protected $isPrimary;

    /**
     * Returns the id of the timeline
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the timeline.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Sets the most recent executed action.
     * @param \Graphox\Timeline\IAction $action
     */
    public function setLast(IAction $action)
    {
        $this->last = $action;
    }

    /**
     * Returns the most recent executed action.
     * @return \Graphox\Timeline\IAction
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * Returns the firts action ever executed.
     * @return \Graphox\Timeline\IAction
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Returns the first action ever executed.
     * @param \Graphox\Timeline\IAction $action
     */
    public function setFirst(IAction $action)
    {
        $this->first = $action;
    }

    /**
     * Returns if this timeline is the public timeline of the user.
     * @return bool
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Sets if the timeline is the public timeline of the user.
     * @param bool $public
     */
    public function setIsPublic($public)
    {
        $this->isPublic = (bool) $public;
    }

    /**
     * Returns if this user if the public timeline of the user.
     * @return bool
     */
    public function isPublic()
    {
        return $this->getIsPublic();
    }

    /**
     * Returns if this timeline is the user's personal timeline.
     * @return bool
     */
    public function getIsPrimary()
    {
        return $this->isPrimary;
    }

    /**
     * Sets if this timeline is the user's personal timeline.
     * @param bool $primary
     */
    public function setIsPrimary($primary)
    {
        $this->isPrimary = (bool) $primary;
    }

    /**
     * Returns if this timeline is the user's personal timeline.
     * @return bool
     */
    public function isPrimary()
    {
        return $this->getIsPrimary();
    }

    /**
     * Sets the newest action and build the chain.
     * @param \Graphox\Timeline\IAction $action
     */
    public function push(IAction $action)
    {
        $current = $this->getLast();

        if ($current) $action->setNext($current);
        else $this->setFirst($action);

        $this->setLast($action);
    }

    /**
     * Sets the first ever action and build the cain.
     * @param \Graphox\Timeline\IAction $action
     */
    public function append(IAction $action)
    {
        $current = $this->getFirst();
        if ($current) $current->setNext($action);
        else $this->setLast($action);
        $this->setFirst($action);
    }

}

