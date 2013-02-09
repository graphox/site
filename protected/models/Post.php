<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property integer $id
 * @property integer $authorId
 * @property string $content
 * @property integer $created
 * @property integer $updated
 *
 * The followings are the available model relations:
 * @property Thread $thread Thread this post lives in
 * @property Forumuser $user User who posted this
 */
class Post extends Neo4jNode
{
	public $authorId;
	public $threadId;
	public $editorId;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'content'			=>	array('type' => 'string'),
			'created'			=>	array('type' => 'integer'),
			'updated'			=>	array('type' => 'integer'),
        ));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function traversals()
	{
		return CMap::mergeArray(parent::traversals(), array(
            'author'=>array(self::HAS_ONE, self::NODE, 'in("_CREATOR_")'),
            'thread'=>array(self::HAS_ONE, self::NODE, 'in("_THREAD_HAS_POST_")'),
            'editor'=>array(self::HAS_ONE, self::NODE, 'in("_UPDATER_")'),
        ));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'id' => 'ID',
            'authorId'=>'Author',
            'threadId'=>'Thread',
            'editorId'=>'Editor',
            'content'=>'Content',
            'created'=>'Created',
            'updated' => 'Updated',
        ));
    }

    /**
     * Manage the created/updated fields
     */
    public function beforeSave()
    {
        if($this->isNewResource)
            $this->created = time();
        $this->updated = time();

        return parent::beforeSave();
    }
	
	/**
	 * Destroy all relationships before deletion
	 */
	public function beforeDelete()
	{
		foreach($this->getRelationships(array('_CREATOR_','_THREAD_HAS_POST_','_UPDATER_')) as $r)
			$r->destroy ();
		
		
		return parent::beforeDelete();
	}
}
