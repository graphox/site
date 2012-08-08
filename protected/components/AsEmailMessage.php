<?php

/** 
 * The model containing the email message
 */

class AsEmailMessage extends CModel
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
