<?php

class BlogEntity extends ENeo4jNode
{
	/**
	 * @return Blogentity returns class
	 */
	public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
    public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'title'		=>	array('type'=>'string'),
            'content'	=>	array('type'=>'string'),
            'source'	=>	array('type'=>'string'),
			'canComment'=>	array('type'=>'boolean'),
			'tags'		=>	array('type'=>'string[]'),
			'createdDate'	=>	array('type'=>'integer'),
			'routeName'		=>	array('type'=>'string'),
        ));
    }
	
	/**
	 * @var array a list of tags
	 */
	public $tags = array();
	
	/**
	 * @var string the input tag string
	 */
	public $tagString = '';
	
	public function init()
	{
		parent::init();
	}
	
	protected function afterFind()
	{
		parent::afterFind();
		$this->generateTagstring();
	}
	
	/**
	 * @return array list or rules
	 */
	public function rules()
	{
		return array(
			array('title, source, canComment, tagString', 'required'),
			array('tagString', 'formatTags'),
		);
	}
	
	/**
	 * Formats the tags into an array
	 * @attribute string $field fielfd to check
	 */
	public function formatTags($field)
	{
		$this->tags = array();
		foreach(explode(',', $this->tagString) as $tag)
		{
			$tag = trim($tag);
			//$tag = preg_replace('^[\w\d]+', '', $tag);
			$this->tags[$tag] = 1;
		}
		
		$this->tags = array_keys($this->tags);
				
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
	
	public function afterValidate()
	{
		parent::afterValidate();
		
		$this->content = Yii::app()->contentMarkup->safeTransform($this->source);
		
		if(!isset($this->createdDate) || !is_numeric($this->createdDate))
			$this->createdDate = time();
		
		$this->routeName = str_replace(' ', '-', $this->title);
		$this->routeName = preg_replace('/[^a-zA-Z0-9\-]/', '', $this->routeName);
	}
	
	public function afterSave()
	{
		parent::afterSave();
	
		
		if(count($this->inRelationships('_CREATOR_')) == 0)
			$this->addRelationshipTo(Yii::app()->user->node, '_CREATOR_');
		else
			$this->addRelationshipTo(Yii::app()->user->node, '_UPDATER_');
	}
	
	public function traversals()
    {
        return array(
            'updaters'	=>	array(self::HAS_MANY, self::NODE, 'out("_UPDATER_")'),
            'creator'	=>	array(self::HAS_ONE, self::NODE, 'out("_CREATOR_")'),
			'blog'		=>	array(self::HAS_ONE, self::NODE, 'in("_BLOG_HAS_POST_")'),
        );
    }
	
	/**
	 * Find the latest posts that are published,
	 * used for the homepage
	 * @todo sort in query
	 * @return array BlogEntity models
	 */
	public function findRecentPublished()
	{
		$arr = self::model()->findAllByQuery('
			g.idx("Blog")[[modelclass:"Blog"]].filter{(it.publish == true)}.out("_BLOG_HAS_POST_")
		');
		
		usort($arr, function($a, $b) {
			return is_numeric($a) && is_numeric($b) && $a->createdDate > $b->createdDate;
		});
		
		return $arr;
	}
	
	/**
	 * Changes the modelclass to DeletedBlogEntity so no search actions will return it anymore.
	 * @param boolean $save = true whether the function should call save(false) after changing the modelaclass.
	 */
	public function delete($save = true)
	{
		$this->modelclass = 'DeletedBlogEntity';
		if($save === true)
			$this->save(false);
	}
}