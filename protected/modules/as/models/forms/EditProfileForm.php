<?php

class EditProfileForm extends Profile
{
	public $page_title;
	public $page_description;
	public $allow_comments = 1;
	public $page_content;
	public $page_markup;
	
	public $profile_img;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function init()
	{
		if($this->page)
		{
			$this->page_title = $this->page->title;
			$this->page_description = $this->page->description;
			$this->page_content = $this->page->content;
			$this->page_markup = $this->page->markup;
		}
		
		return parent::init();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(	
			array('page_title, page_description, page_content, page_markup', 'required', 'on' => 'pageisset'),
			array('homepage', 'url'),
			array('user_id, avatar_img_id, page_id', 'unsafe'),
			array('page_title, page_description, homepage', 'length', 'max'=>500),

			array('page_title,page_description,allow_comments,page_content,page_markup,profile_img', 'safe'),
		);
		
	}
	
	public function afterSave()
	{
		if($this->scenario == 'pageisset')
		{
			$page = $this->page;
			if(!$page)
				$page = new Pages;
		
			$page->module = 'profile';
			$page->editor_id = Yii::app()->user->id;
			$page->uri = '';

			$page->title = $this->page_title;
			$page->description = $this->page_description;
			$page->content = $this->page_content;
			$page->markup = $this->page_markup;
			
			if(!$page->validate())
				foreach($page->getErrors() as $key => $error)
					foreach($error as $msg)
						$this->addError($key, $msg);
				
			
		}
		
		return parent::afterSave();
	}
}
