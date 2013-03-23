<?php


/**
 * The entity on a timeline that points to a verb.
 * @package Graphox\Timeline
 * @author killme
 */

namespace Graphox\Timeline;

/**
 * @package Graphox\Timeline
 */
interface IAction
{
	/**
	 * Sets the id of the action
	 * @param int $id
	 */
	public function setId($id);

	/**
     * Returns the id of the action.
     * @return int
     */
	public function getId();

	/**
	 * Sets the verb this action points to.
	 * @param \Graphox\Timeline\Verb $verb
	 */
	public function setVerb(Verb $verb);

	/**
	 * Returns the verb of this action.
	 * @return \Graphox\Timeline\Verb
	 */
	public function getVerb();

	/**
	 * Get the action executed before this one.
	 * @return \Graphox\Timeline\IAction
	 */
	public function getNext();

	/**
	 * Sets the action before this one.
	 * @param \Graphox\Timeline\IAction $action
	 */
	public function setNext(IAction $action);
}

