<?php

/**
 * This is the model class for table "forum".
 *
 * The followings are the available columns in table 'forum':
 * @property integer $id
 * @property string $title Forum title
 * @property string $description Forum description
 * @property integer $listorder
 * @property boolean $isLocked Create new threads in forum? (ignored for categories)
 *
 * The followings are the available model relations:
 * @property Forum[] $subforums
 * @property Thread[] $threads
 */
class Forum extends Neo4jNode
{
	public $parentId;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'title'			=>	array('type' => 'string'),
			'description'	=>	array('type' => 'string'),
			'descriptionSource'	=>	array('type' => 'string'),
            'isLocked'		=>	array('type' => 'boolean'),
			'listorder'		=>	array('type' => 'integer'),
        ));
    }
	
	public function getIs_locked()
	{
		return $this->isLocked;
	}
	
	public function setIs_locked($value)
	{
		$this->isLocked = $value;
	}
	
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title', 'length', 'max'=>120),
            array('parentId, description, isLocked', 'safe'),
            array('listorder', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title, descriptionSource', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
	public function traversals()
	{
		return CMap::mergeArray(parent::traversals(), array(
			'parent' => array(self::HAS_ONE, self::NODE,'in("_SUB_FORUM_")'),
			'parentRelation' => array(self::HAS_ONE, self::NODE,'inE("_SUB_FORUM_")'),
			'subForums' => array(self::HAS_MANY, self::NODE,'out("_SUB_FORUM_")'),
			'threads' => array(self::HAS_MANY, self::NODE,'out("_FORUM_HAS_THREAD_")'),
		));
	}
	
	/**
	 * TODO: 1 query to retrieve it all!
	 */
	public function getParents()
	{
		$nodes = array();
		$current = $this->parent;
		while($current !== null)
		{
			$nodes[] = $current;
			$current = $current->parent;
		}
		
		return $nodes;
	}

	public function getAllSubForums()
	{
		$nodes = array();
		
		$getsub = function(&$node, &$getsub) use ($nodes)
		{
			foreach($node->subForums as $node)
			{
				$nodes[] = $node;
				$getsub($node, $getsub);
			}
		};
		
		$getsub($this,$getsub);
		
		return $nodes;
	}
	
	protected function afterConstruct()
	{
		parent::afterConstruct();
		$this->parentId = $this->parent->id;
		
	}
	protected function afterValidate()
	{
		parent::afterValidate();
		
		$this->descriptionSource = Yii::app()->contentMarkup->safeTransform($this->description);
	}
	public function afterSave()
	{
		parent::afterSave();
		
		//foreach ($this->getRelationships('_SUB_FORUM_', 'in') as $rel)
		//		$rel->deleteById($rel->id);
		
		if($this->parentId !== NULL)
		{
			if($this->parentRelation !== NULL)
				$this->parentRelation->deleteById($this->parentRelation->id);
			
			$r = new _SUB_FORUM_;
			$r->setStartNode($this->parentId);
			$r->setEndNode($this->id);
			//$r->save();
			
			die;
		}
	}
	

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'id' => 'ID',
            'parentId' => 'Parent ID',
            'title'=>'Title',
            'description'=>'Description',
            'listorder' => 'Listorder',
            'isLocked' => 'Is locked?',
        ));
    }

    /**
     * Return the url to this forum
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('/forum/forum/view', array('id'=>$this->id));
    }

    /**
     * Returns breadcrumbs array to this forum
     */
    public function getBreadcrumbs($currentlink=false)
    {
        // Get the "path" from our parent to null
        $breadcrumbs = array();
		
		foreach($this->getParents() as $forum)
			$breadcrumbs[$forum->title] = array('/forum/forum/view', 'id'=>$forum->id);

        $breadcrumbs = array_merge(
            array('Forum'=>array('/forum')),
            array_reverse($breadcrumbs)
        );

        if(!$this->isNewRecord)
        {
            $breadcrumbs = array_merge($breadcrumbs, $currentlink
               ?array(CHtml::encode($this->title)=>array('/forum/forum/view','id'=>$this->id))
               :array(CHtml::encode($this->title))
            );
        }
        return $breadcrumbs;
    }

    /**
     * This gets rendered in the forum table.
     * Showing forum title, with link to forum, forum description and a list of sub forums
     * if applicable.
     */
    public function renderForumCell()
    {
        $result =
            '<div class="name">'. CHtml::link(CHtml::encode($this->title), $this->url) .'</div>'.
            '<div class="level2">'. $this->description .'</div>';

        $subforums = $this->subForums;
        if($subforums)
        {
            $subarr = array();
            foreach($subforums as $forum)
            {
                $subarr[] = CHtml::link(CHtml::encode($forum->title), $forum->url);
            }
            $result .= '<div class="level3"><b>Sub forums:</b> '. implode(', ', $subarr) .'</div>';
        }
        return $result;
    }

    /**
     * This gets rendered in the forum table.
     * Showing last post subject, with link to it, time of post, and name of poster, with link.
     */
    public function renderLastpostCell()
    {
        $lastpost = $this->lastPost;
        if(null == $lastpost) return '<div style="text-align:center;">-</div>';

        $thread = $lastpost->thread;
        $author = $lastpost->author;

        $threadlink = CHtml::link(CHtml::encode($thread->subject), $thread->url);
        $authorlink = CHtml::link(CHtml::encode($author->displayName), $author->url);

        return '<div class="name">'. $threadlink .'</div>'.
                '<div class="level2">'. Yii::app()->controller->module->format_date($lastpost->created) .'</div>'.
                '<div class="level3">by '. $authorlink .'</div>';
    }
	
	public function getThreadCount()
	{
		return 999;
	}

	public function getPostCount()
	{
		return 999;
	}
	
	public function getLastPost()
	{
		return NULL;
	}
}
