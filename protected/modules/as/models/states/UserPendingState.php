<?php

class UserPendingState extends AState
{
	public function activate()
	{
		$machine = $this->getMachine();
		$user = $machine->getOwner();
		$user->status = "active";
		$user->save();
		$machine->transition("active");
	}
}
