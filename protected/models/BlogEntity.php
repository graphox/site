<?php

class BlogEntity extends PageEntity
{
	/**
	 * @var $metaMap array an array containing the metadata vars to include.
	 */
	protected $metaMap = array(
		'title',
		'content',
		'source',
		'can_comment',
		'tags',
	);
	
	/**
	 * @var array a list of tags
	 */
	public $tags = array();
	
	/**
	 * @var string the input tag string
	 */
	public $tagString = 'a, b';
	
	protected function init()
	{
		parent::init();
		
		if($this->isNewRecord)
			$this->subtype_id = EntityType::model()->findByAttributes(array('name' => 'blog'))->id;
		

		$this->generateTagstring();
	}
	
	/**
	 * @return array list or rules
	 */
	public function rules()
	{
		return array(
			array('title, source, access, status', 'required'),
			array('tagString', 'formatTags'),
			array('access', 'application.components.EntityAccessValidator'),
			array('status', 'application.components.EntityStatusValidator'),
		);
	}
	
	/**
	 * Formats the tags into an array
	 * @attribute string $field fielfd to check
	 */
	public function formatTags($field)
	{
		foreach(explode(',', $this->tagString) as $tag)
		{
			$tag = trim($tag);
			//$tag = preg_replace('^[\w\d]+', '', $tag);
			$this->tags[] = $tag;
		}
				
		$this->generateTagstring();
	}
	
	/**
	 * Regenerates the tag string
	 */
	public function generateTagstring()
	{
		if(!is_array($this->tags))
			$this->tags = array();
		
		$this->tagString = implode($this->tags, ', ');	
	}
		
}