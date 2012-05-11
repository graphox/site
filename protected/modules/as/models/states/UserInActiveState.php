<?php

class UserInActivateState extends AState
{
	/**
	 * Reactivates the user's account
	 */
	public function reactivate()
	{
		$machine = $this->getMachine();
		$user = $machine->getOwner();
		$user->status = "active";
		$user->save();
		$machine->transition("active");
	}
	/**
	 * Invoked before the state is transitioned to
	 */
	public function beforeEnter()
	{
		if ($this->getMachine()->getState()->name == "pending") {
			// invalid state transition, user cannot go pending -> deactivated
			return false;
		}
		return parent::beforeEnter();
	}
	/**
	 * Raised when the state is transitioned to
	 * @param AState $from the previous state
	 */
	public function afterEnter(AState $from)
	{
		parent::afterEnter($from);
		Yii::log($this->getMachine()->getOwner()->name." deactivated their account :(");
	}
}

