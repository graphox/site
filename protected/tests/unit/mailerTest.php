<?php

class mailerTest extends CTestCase
{
	public function testSendmail()
	{
		$mailer = Yii::app()->mailer;
		
		$this->assertNotEquals(NULL, $mailer);
		
		$message = new AsEmail($mailer->defaultAttributes);
		
		$message->subject = 'MAAAAA SubJECT';
		
		$message->to = 'bram.wall@gmail.com';
		$message->body = 'Hello WORLD';
		$message->html_body = 'hello <strong>World</strong>';
		
		$this->assertEquals(1, $mailer->send($message));
		
	}

}
