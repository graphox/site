<?php

/**
	@class AsMailer
	alphaserv mailer component
*/

class AsMailer extends CComponent
{
	/**
		@var type
		Type of the mailserver, can be sendmail, mail, smtp
	*/
	public $type;
	
	/**
		@var host
		the hostname of the mailserver
	*/
	public $host;
	
	/**
		@var sendmailPath
		the path to your sendmail installation
	*/
	public $sendmailPath;
	
	/**
		@var port
		the port of the server
	*/
	public $port;
	
	/**
		@var username
		the username of the mailserver
	*/
	public $username;
	
	/**
		@var password
		the password of the mailserver
	*/
	public $password;
	
	/**
		@var defaultAttributes
		the default email model attributtes
	*/
	public $defaultAttributes;
	
	/**
		@function init
		initializes the class and loads the swift autoloader
	*/
	public function init()
	{
		require 'swift_required.php';
		Yii::registerAutoloader(array('Swift','autoload'));
	}
	
	/**
		@var transport
		the swift transport class
		@readonly
	*/
	
	public function getTransport()
	{
		static $transport;
		
		if(!isset($transport))
		{
			switch($this->type)
			{
				case 'smtp':
					$transport = Swift_SmtpTransport::newInstance($this->host, $this->port)
						->setUsername($this->username)
						->setPassword($this->password);
					break;
					
				case 'sendmail':
					$transport = Swift_SendmailTransport::newInstance($this->sendmailPath);
					break;
					
				case 'mail':
					$transport = Swift_MailTransport::newInstance();
					break;
					
				default:
					throw new CException(Yii::t('as.mailer', 'Unkown transport type: {type}', array('{type}' => $this->type)));
			}		
		}
		
		return $transport;
	}
	
	/**
		@var mailer
		the swift mailer class
		@readonly
	*/
	public function getMailer()
	{
		static $mailer;
		
		if(!isset($mailer))
			$mailer = Swift_Mailer::newInstance($this->transport);
		
		return $mailer;
	}
	
	/**
		@function send
		Sends an AsEmail model to the receiver
	*/
	public function send(AsEmail $model)
	{
		Yii::log('Sending new email message '.print_r($model, true), 'info', 'as.mailer');
		#translate into swift message
		$message = Swift_Message::newInstance()
			->setSubject($model->subject)
			->setFrom($model->from)
			->setTo(is_array($model->to) ? $model->to: array($model->to))
			->setBody($model->body);
		
		if(!empty($model->body_html))
			$message->addPart($model->body_html, 'text/html'); #html body

		foreach($model->attachments as $attachment)
			$message->attach($attachment);
		
		return $this->mailer->send($message);
	}
	
	
	
}

/*
	The model containing the email message
*/

class AsEmail extends CModel
{
	/**
		@function rules
		get the safe attributes for massive assignment
	*/
	public function rules()
	{
		return array(
			array('from, to, subject, body, html_body', 'safe'),
		);
	}
	
	public function __construct($defaults = NULL)
	{
		$this->attributes = $defaults;
		
		if($defaults === NULL)
			$this->attributes = Yii::app()->mailer->defaultAttributes;
		
		#note:	no parent function to call
	}
	
	public function attributeNames()
	{
	}

	/**
		@var from
		the sender as array('name', 'email')
	*/
	public $from;
	
	/**
		@var to
		the email address of the receiver
	*/
	public $to;
	
	/**
		@var subject
		the subject of the message
	*/
	public $subject;
	
	/**
		@var body
		@var html_body
		the body of the message as plaintext and as html
	*/
	public $body;
	public $html_body;
	
	/**
		@var attachments
		an array containing swift attachment objects
	*/
	public $attachments = array();
	
	/**
		@function addAttachment
		adds an attachment from a path
	*/
	public function addAttachment($path)
	{
		$this->attachments[] = Swift_Attachment::fromPath($path);
	}
}
