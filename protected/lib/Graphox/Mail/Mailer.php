<?php

namespace Graphox\Mail;

/**
 * Wrapper class for swiftmailer
 */
class Mailer extends \CApplicationComponent
{
	/**
	 * @var mixed the current transport array as set in the config or the initialized class.
	 */
	protected $transport;
	
	/**
	 * Sets the tranport class
	 * @param mixed $transport array or object represitation of the transport
	 * @return \Graphox\Mail\Mailer return $this
	 * @throws \CException
	 */
	public function setTransport($transport)
	{
		if(!is_array($transport) && !is_object($transport))
			throw new \CException('Invalid transport');
		
		$this->transport = $transport;
		
		return $this;
	}
	
	/**
	 * Autoloads the transport class and returns it
	 * @return \Swift_SmtpTransport
	 */
	public function getTransport()
	{
		if(is_array($this->transport))
		{
			$cfg = $this->transport;
			
			if(!isset($cfg['class']))
				$cfg['class'] = '\Swift_SmtpTransport';
			
			$this->transport = new $cfg['class'](
					isset($cfg['host']) ? $cfg['host'] : 'localhost',
					isset($cfg['port']) ? $cfg['port'] : 25,
					isset($cfg['encryption']) ? $cfg['encryption'] : null
			);
			
		}
		
		return $this->transport;
	}
	
	/**
	 * Singleton for the Swift_Mailer class
	 * @staticvar \Swift_Mailer $mailer
	 * @return \Swift_Mailer
	 */
	protected function getMailer()
	{
		static $mailer;
		
		if(!isset($mailer))
			$mailer = new \Swift_Mailer($this->getTransport());
		
		return $mailer;
	}
	
    /**
     * Send the given Message like it would be sent in a mail client.
     *
     * All recipients (with the exception of Bcc) will be able to see the other
     * recipients this message was sent to.
     *
     * Recipient/sender data will be retrieved from the Message object.
     *
     * The return value is the number of recipients who were accepted for
     * delivery.
     *
     * @param Swift_Mime_Message $message
     * @param array              $failedRecipients An array of failures by-reference
     *
     * @return integer
     */
	public function sendMessage(\Swift_Mime_Message $message, &$failedRecipients = null)
	{
		return $this->getMailer()->send($message, $failedRecipients);
	}
}