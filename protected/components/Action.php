<?php

/**
 * An action an User can perform on a model.
 * 
 * may be called with ajax.
 * permissions are checked in the controller layer
 */
abstract class Action extends CComponent
{
	abstract const NAME = 'noAction';
	/**
	 * Performs the action
	 */
	abstract function perform(Neo4JNode &$node);
}