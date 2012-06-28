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
	
	public function load($profile)
	{
		$this->attributes = $profile->attributes;
		$this->isNewRecord = false;
			
		$this->user_id = $profile->user_id;
		$this->avatar_img_id = $profile->avatar_img_id;
		$this->page_id	= $profile->page_id;
		$this->id = $profile->id;
		
		$this->init();
	}
	
	public function init()
	{
		$this->page = Pages::model()->findByPk($this->page_id);
	
		if($this->page)
		{
			$this->page_title = $this->page->title;
			$this->page_description = $this->page->description;
			$this->page_content = $this->page->content;
			$this->page_markup = $this->page->markup;
		}
		else
		{
			$this->page = new Pages;
		
			$this->page->module = 'profile';
			$this->page->editor_id = Yii::app()->user->id;
			$this->page->uri = '/';
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

			array('page_markup', 'checkAllowedMarkup'),
			array('page_title,page_description,allow_comments,page_content,page_markup,profile_img', 'safe'),
		);
		
	}
	
	public function checkAllowedMarkup($field, $options)
	{
		static $allowed;
		
		if(!isset($allowed))
			$allowed = ContentMakeup::userAllowed();
		
		if(!isset($allowed[$this->$field]))
			$this->addError($field, 'Invalid makrup style: '.$this->$field);
	}
	
	public function save( $runValidation=true, $attributes=NULL)
	{
		if(parent::save($runValidation, $attributes))
		{
			if($this->scenario == 'pageisset')
			{
				$this->page->title = $this->page_title;
				$this->page->description = $this->page_description;
				$this->page->content = $this->page_content;
				$this->page->markup = $this->page_markup;
			
				if(!$this->page->validate() || !$this->page->save())
					throw new Exception(print_r($this->page->getErrors(), true));
			
				$this->page_id = $this->page->id;

				if(!parent::save())
					throw new Exception(print_r($this->page->getErrors(), true));
			}
			
			return true;
		}
		return false;
	}
}
