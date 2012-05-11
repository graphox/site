<?php

class UserActiveState extends AState
{
	public function deactivate()
	{
		$machine = $this->getMachine();
		$user = $machine->getOwner();
		$user->status = "inactive";
		$user->save();
		$machine->transition("inactive");
	}
	/**
	 * Raised when the state is transitioned to
	 * @param AState $from the previous state
	 */
	public function afterEnter(AState $from)
	{
		parent::afterEnter($from);
		if ($from->name == "pending") {
			// send welcome email
		}
		else {
			// send welcome back email
		}
	}
}
