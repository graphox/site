<?php

/**
 * Class to cycle over one or more timelines's actions.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

use \Doctrine\Common\Collections\ArrayCollection,
    \Graphox\Modules\User\User,
    Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\Selectable,
    Doctrine\Common\Collections\Criteria;

/**
 * Class to cycle over one or more timelines's actions.
 * @package Graphox\Timeline
 */
class TimelineIterator implements Collection, Selectable
{
    const DEFAULT_SIZE = 15;

    /**
     * @var \Doctrine\Common\Collections\Arraycollection timelines to iterate
     */
    private $timelines;

    /**
     * Amount of items to look for in the timelines.
     * @var int
     */
    private $size = self::DEFAULT_SIZE;

    /**
     * Actions from the timelines.
     * @var array
     */
    private $data;

    /**
     * Initializes the class.
     */
    public function __construct()
    {
        $this->timelines = new ArrayCollection;
    }

    /**
     * Creates a TimelineIterator from an user object.
     * @param \Graphox\Modules\User\User $user
     * @param int $size
     * @return \Graphox\Timeline\TimelineIterator
     */
    public static function createFromUser(User $user, $size = self::DEFAULT_SIZE)
    {
        $iterator = new TimelineIterator;

        $timelineRepository = \Yii::app()->neo4j->getRepository('\Graphox\Timeline\Timeline');
        $iterator->addTimeline(
                $timelineRepository->findPersonalTimeline($user));

        foreach ($timelineRepository->findFollowingTimelines($user) as $timeline)
        {
            $iterator->addTimeline($timeline);
        }

        $iterator->setSize($size);

        return $iterator;
    }

    /**
     * Sets the timelines to search for.
     * @param array $timelines
     */
    public function setTimelines($timelines)
    {
        $this->timelines = new ArrayCollection($timelines);
    }

    /**
     * Returns the timelines that are searched
     * @return array
     */
    public function getTimelines()
    {
        return $this->timelines;
    }

    /**
     * Adds a timeline to the list of timelines to search actions.
     * @param \Graphox\Timeline\Timeline $timeline
     */
    public function addTimeline(Timeline $timeline)
    {
        $this->getTimelines()->add($timeline);
    }

    /**
     * Removes a timeline to the list of timelines to search actions.
     * @param \Graphox\Timeline\Timeline $timeline
     */
    public function removeTimeline(Timeline $timeline)
    {
        $this->getTimelines()->removeElement($timeline);
    }

    /**
     * Sets the amount of actions to look up.
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
    }

    /**
     * Returns the amount of actions that are looked up.
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Fetches the data from the database.
     */
    private function fetch()
    {
        $this->data = \Yii::app()->neo4j->getRepository('\Graphox\Timeline\Timeline')->findUpdates(
                $this->getTimelines()->toArray());
    }

    /**
     * Returns, and lazy loads, the data.
     * @return array
     */
    public function getData()
    {
        if (!isset($this->data)) $this->fetch();

        return $this->data;
    }

    /**
     * Turn the iterator into an array.
     * @return array
     */
    public function toArray()
    {
        return $this->getData()->toArray();
    }

    /**
     * Turns the iterator into an ArrayCollection
     * @return \Graphox\Timeline\ArrayCollection
     */
    public function toArrayCollection()
    {
        return new ArrayCollection($this->toArray());
    }

    /*
     * Iterator stuff
     */

    /**
     * Returns the first item
     * @return IAction
     */
    public function first()
    {
        return $this->getData()->first();
    }

    /**
     * Returns the last item
     * @return IAction
     */
    public function last()
    {
        return $this->getData()->last();
    }

    /**
     * Returns the current key
     * @return mixed
     */
    public function key()
    {
        return $this->getData()->key();
    }

    /**
     * Returns the next element
     * @return IAction
     */
    public function next()
    {
        return $this->getData()->next();
    }

    /**
     * Returns the current element
     * @return IAction
     */
    public function current()
    {
        return $this->getData()->current();
    }

    /**
     * Iterator is read only.
     *
     * @param mixed $key
     * @throws Exception
     */
    public function remove($key)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Iterator is read only.
     *
     * @param mixed $element
     * @throws Exception
     */
    public function removeElement($element)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Returns whether the offset exists
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->getData()->offsetExists($offset);
    }

    /**
     * Returns wether the specified offset exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetGet($offset)
    {
        return $this->getData()->offsetGet($offset);
    }

    /**
     * Iterator is read only.
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Iterator is read only.
     *
     * @param mixed $offset
     * @throws Exception
     */
    public function offsetUnset($offset)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Returns whether the iterator contains a specific key.
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key)
    {
        return $this->getData()->containsKey($key);
    }

    /**
     * Check wether the iterator contains a specific element
     * @param mixed $element
     * @return bool
     */
    public function contains($element)
    {
        return $this->getData()->contains($element);
    }

    /**
     * Tests if an element exists with a closure
     * @param \Closure $p
     * @return bool
     */
    public function exists(\Closure $p)
    {
        return $this->getData()->exists($p);
    }

    /**
     * Returns the index of an element
     * @param mixed $element
     * @return integer
     */
    public function indexOf($element)
    {
        return $this->getData()->indexOf($element);
    }

    /**
     * Returns the specified element
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->getData()->get($key);
    }

    /**
     * Returns the keys
     * @return array
     */
    public function getKeys()
    {
        return $this->getData()->getKeys();
    }

    /**
     * Returns the values.
     * @return array
     */
    public function getValues()
    {
        return $this->getData()->getValues();
    }

    /**
     * Return the element count
     * @return integer
     */
    public function count()
    {
        return $this->getData()->count();
    }

    /**
     * TimelineIterator is read only.
     * @param mixed $key
     * @param mixed $value
     * @throws Exception
     */
    public function set($key, $value)
    {
        throw new Exception('Iterator is read only.');
    }
    /**
     * TimelineIterator is read only.
     * @param mixed $value
     * @throws Exception
     */
    public function add($value)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Returns whether the iterator is empty.
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getData()->isEmpty();
    }

    /**
     * Returns a raw iterator.
     * @return \Iterator
     */
    public function getIterator()
    {
        return $this->getData()->getIterator();
    }

    /**
     * Create a new ArrayCollection with array_map applied
     * @param \Closure $func
     * @return ArrayCollection
     */
    public function map(\Closure $func)
    {
        return $this->getData()->map($func);
    }

    /**
     * Create a new ArrayCollection with array_filter applied
     * @param \Closure $p
     * @return ArrayCollection
     */
    public function filter(\Closure $p)
    {
        return $this->getData()->filter($p);
    }

    /**
     * Executes the closure for every element.
     * @param \Closure $p
     * @return bool
     */
    public function forAll(\Closure $p)
    {
        return $this->getData()->forAll($p);
    }

    /**
     * Divide the values into 2 new ArrayCollections
     * @param \Closure $p
     * @return array
     */
    public function partition(\Closure $p)
    {
        return $this->getData()->partition($p);
    }

    /**
     * Create a string from the class
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * Iterator is read only.
     * @throws Exception
     */
    public function clear()
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Iterator is read only.
     *
     * @param mixed $offset
     * @param mixed $length
     * @throws Exception
     */
    public function slice($offset, $length = null)
    {
        throw new Exception('Iterator is read only.');
    }

    /**
     * Create a new ArrayCollection from the criteria
     * @param \Doctrine\Common\Collections\Criteria $criteria
     */
    public function matching(Criteria $criteria)
    {
        $this->getData()->matching($criteria);
    }

}

