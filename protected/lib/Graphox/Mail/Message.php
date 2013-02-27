<?php

namespace Graphox\Mail;

class Message extends \Swift_Message
{
	public function setTo($addresses, $name = null)
	{
		if($addresses instanceof \Graphox\Modules\User\Email)
			$address = $address->getEmail();
		
		return parent::setTo($addresses, $name);
	}
}

