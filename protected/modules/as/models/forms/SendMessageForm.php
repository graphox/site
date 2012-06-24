<?php

class SendMessageForm extends PmMessage
{
	//name of the receiver
	public $receiver;

	public function rules()
	{
		return array(
			array('title, content, receiver', 'required'),
			
			array('receiver', 'valid_user'),
			array('title', 'length', 'max'=>20, 'min' => 3,'message' => 'Incorrect title (length between 3 and 20 characters).'),
			array('content', 'length', 'min' => 5,'message' => 'Incorrect content (length between 3 and 20 characters).'),
		);
	}
	
	public function valid_user()
	{
		$user = User::model()->FindByAttributes(array('username' => $this->receiver));
			
		if(!$user)
			$this->addError('receiver', 'Could not find that user');
	}
	
	public function beforeSave()
	{
		if(parent::beforeSave())
		{
			$this->sender_id = Yii::app()->user->id;
		
			if(!isset($this->receiver_id))
			{
				$user = User::model()->FindByAttributes(array('username' => $this->receiver));
			
				if(!$user)
				{
					$this->addError('receiver', 'Could not find that user');
					return false;
				}
			
				$this->receiver_id = $user->id;
			}
		
			if(!isset($this->receiver_dir_id))
			{
				$dir = PmDirectory::model()->findByAttributes(array('user_id' => $this->receiver_id, 'name' => 'inbox'));
			
				if(!$dir)
				{
					$dir = new PmDirectory;
					$dir->name = 'inbox';
					$dir->user_id = $this->receiver_id;
					$dir->save();
				}				
			
				$this->receiver_dir_id = $dir->id;
			}
		
			$this->read = $this->receiver_deleted = 0;
			$this->sended_date = new CDbExpression('NOW()');
			
			return true;
		}
		
		return false
	}
}
