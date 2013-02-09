<?php

class AsCommentContent extends AsContentType
{
	protected $formView = 'as.views.contentType.comment.form';
	
	public function rules()
	{
		$rules = parent::rules();
		
		return $rules;
	}
	
	public function initComment($parent, $class)
	{
		$this->type_id = ContentType::model()->findByAttributes(array(
			'class' => $class
		))->id;
		
		$this->parent_id = $parent->id;
		$this->widgets_enabled = 0;
		$this->can_comment = 1;
	}
}
