<?php

/**
 * Mail message
 * @package Graphox\Mail
 * @author killme
 */

namespace Graphox\Mail;

/**
 * Mail message
 * @package Graphox\Mail
 */
class Message extends \Swift_Message
{

    /**
     * {@inheritdoc}
     */
    public function setTo($addresses, $name = null)
	{
		if($addresses instanceof \Graphox\Modules\User\Email)
			$address = $address->getEmail();

		return parent::setTo($addresses, $name);
	}
}

