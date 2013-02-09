<?php
/**
 * This is the model class for table "thread".
 *
 * The followings are the available columns in table 'thread':
 * @property integer $id
 * @property string $subject Thread subject
 * @property boolean $isSticky Sticky thread
 * @property boolean $isLocked Locked thread
 * @property string icon Thread icon
 * @property string $notes Thread notes
 * @property integer $view_count (stat cache) Number of time thread was read
 *
 * The followings are the available model relations:
 * @property Forum $forum Forum this thread lives in
 */
class Thread extends  Neo4jNode
{

	public $forumId;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Thread the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'subject'			=>	array('type' => 'string'),
			'isSticky'	=>	array('type' => 'boolean'),
            'isLocked'		=>	array('type' => 'boolean'),
			'icon'		=>	array('type' => 'stringr'),
			'subject'			=>	array('type' => 'notes'),
			'viewCount'			=>	array('type' => 'integer'),
			'created'			=>	array('type' => 'integer'),
        ));
    }
	
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subject', 'required'),
            array('subject', 'length', 'max'=>120),
            /**
             * Normally, this would allow anyopne to set these, even non-admins,
             * however, since normal users do not create threads directly, hut
             * as part of a post (PostForm model), this one is only used by
             * admins, who acgtually need to be able to set these.
             */
            array('isSticky, isLocked', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('subject', 'safe', 'on' => 'search'),
        );
    }

	public function traversals()
	{
		return CMap::mergeArray(parent::traversals(), array(
            'forum' => array(self::HAS_ONE, self::NODE,'in("_FORUM_HAS_THREAD_")'),
			'posts' => array(self::HAS_MANY, self::NODE,'out("_THREAD_HAS_POST_")'),
        ));
    }
	
	public function getPostCount()
	{
		return count($this->posts);
	}
	

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() 
	{
        return array_merge(parent::attributeLabels(), array(
            'id' => 'ID',
            'forumId' => 'Forum ID',
            'subject'=>'Subject',
            'isSticky' => 'Is sticky?',
            'isLocked' => 'Is locked?',
            'view_count'=>'View count',
            'created' => 'Created',
        ));
    }

    /**
     * Manage the created fields
     */
    public function beforeSave()
    {
        if($this->isNewRecord)
            $this->created = time();

        return parent::beforeSave();
    }
	
	public function afterSave()
	{
		parent::afterSave();
		
		if($this->forum === NULL && $this->forumId !== NULL)
			Forum::model()->findById($this->forumId)->addRelationshipTo($this, '_FORUM_HAS_THREAD_');
	}

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('subject', $this->subject, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Return the url to this thread
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('/forum/thread/view', array('id'=>$this->id));
    }

    /**
     * Returns breadcrumbs array to this forum
     */
    public function getBreadcrumbs($currentlink=false)
    {
        return array_merge(
            $this->forum->getBreadcrumbs(true),
            ($currentlink?array(CHtml::encode($this->subject)=>array('/forum/thread/view', 'id'=>$this->id)):array(CHtml::encode($this->subject)))
            // array(isset($this->subject)?$this->subject:'New thread')
        );
    }

    /**
     * Return the first post in this thread (or null)
     */
    public function getFirstPost()
    {
        return isset($this->posts[0]) ? $this->posts[0] : NULL;
    }

    /**
     * Return the last post in this thread (or null)
     */
    public function getLastPost()
    {
		return @end($this->posts);
    }

    public function renderSubjectCell()
    {
        $firstpost = $this->firstPost;
        if(null == $firstpost) return '<div style="text-align:center;">-</div>';

        $subjlink = CHtml::link(CHtml::encode($this->subject), $this->url);
        $authorlink = CHtml::link(CHtml::encode($firstpost->author->displayName), $firstpost->author->url);

        return '<div class="name">'. $subjlink .'</div>'.
                '<div class="level2">by '. $authorlink .'</div>';
    }

    public function renderLastpostCell()
    {
        $lastpost = $this->lastPost;
        if(null == $lastpost) return '<div style="text-align:center;">-</div>';

        $author = $lastpost->author;

        $authorlink = CHtml::link(CHtml::encode($author->displayName), $author->url);

        return '<div class="level2">'. Yii::app()->controller->module->format_date($lastpost->created) .'</div>'.
                '<div class="level3">by '. $authorlink .'</div>';
    }
	
	/**
	 * Destroy all relationships before deletion
	 */
	public function beforeDelete()
	{
		foreach($this->posts as $post)
			$post->destroy();
		
		foreach($this->getRelationships(array('_FORUM_HAS_THREAD_','_THREAD_HAS_POST_')) as $r)
			$r->destroy();
		
		return parent::beforeDelete();
	}

}
