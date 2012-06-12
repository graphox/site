<?php

class AddTopicForm extends ForumTopic
{
	public $message;

	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('message', 'required');
//		$rules[] = array('message', 'length', 'max'=>20, 'min' => 3,'message' => Yii::t('as::user::register', "Incorrect message (length between 3 and 20 characters).")),

		return $rules;
	}
	
	public function save($runValidation=true, $attributes=null)
	{
		$result = parent::save($runValidation, $attributes);
		
		if($result)
		{
			$message = new ForumMessage;
			$message->user_id = Yii::app()->user->id;
			$message->topic_id = $this->id;
			$message->title = $this->description;
			$message->content = $this->message;
			$message->date_added = $message->date_changed = new CDbExpression('NOW()');
			
			if(!$message->save())
			{
				$this->addError('message', print_r($message->getErrors(), true));
				return false;
			}
		}
		
		return $result;
	}
}
