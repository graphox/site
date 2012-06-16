<?php

class GalleryForm extends Gallery
{
	public $title;
	public $description;
	public $content;
	public $allow_comments;
		
	public $images;

	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}

	public function rules()
	{
		$rules = parent::rules();

		array_push($rules, array('description, title, content, images', 'required'));
		
		return $rules;
	}
	
	public function save()
	{
		$page = Pages::model()->findByPk($this->page_id);
		
		if($page === null)
		{
			$page = new Pages;
			$page->module = 'gallery';
			$page->uri = '/';
			$page->acl_object_id = AccessControl::GetObjectByName('site')->id;		
		}		
		
		$page->title = $this->title;
		$page->description = $this->description;
		$page->content = $this->content;
		$page->allow_comments = ($page->allow_comments == 1) ? 1 : 0;
		$page->editor_id = Yii::app()->user->id;

		if(!$page->save(false))
		{
			$this->addErrors($page->getErrors());
			return false;
		}

		$this->page_id = $page->id;
				
		if(!$this->getIsNewRecord())
		{
			$images = GalleryImage::model()->findAllByAttributes(array('gallery_id' => $this->id));
			
			foreach($images as $img)
			{
				$key = array_search($img->id, $this->images);
				
				#remove images thate are not needed anymore
				if($key === false)
					$img->delete();
					
				#these are already in the database
				else
					unset ($this->images[$key]);
					
			}
		}
		
		$res = parent::save();
		
		if(!$res)
			return $res;

		foreach($this->images as $image)
		{
			$img = new GalleryImage;
			$img->gallery_id = $this->id;
			$img->image_id = $image;
			
			if(!$img->save())
				return print_r($img->getErrors()) && false;
		}		
		
		return true;
	}
	
	function load()
	{
		$page = Pages::model()->findByPk($this->page_id);
		
		if(!$page)
			throw new Exception('Malformated Database! page_id');
		
		$this->title = $page->title;
		$this->description = $page->description;
		$page->content = $page->content;
		$page->allow_comments = $page->allow_comments;
		
		$images = GalleryImage::model()->findAllByAttributes(array('gallery_id' => $this->id));
		
		foreach($images as $img)
			$this->images[] = $img->id;
	}
}
