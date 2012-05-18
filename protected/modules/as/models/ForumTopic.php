<?php

/**
 * This is the model class for table "forum_topic".
 *
 * The followings are the available columns in table 'forum_topic':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $forum_id
 * @property integer $acl_object_id
 *
 * The followings are the available model relations:
 * @property ForumMessage[] $forumMessages
 * @property Forum $forum
 * @property AclObject $aclObject
 */
class ForumTopic extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ForumTopic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'forum_topic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, status, forum_id, acl_object_id', 'required'),
			array('id, status, forum_id, acl_object_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('description', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, status, forum_id, acl_object_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'forumMessages' => array(self::HAS_MANY, 'ForumMessage', 'topic_id'),
			'forum' => array(self::BELONGS_TO, 'Forum', 'forum_id'),
			'aclObject' => array(self::BELONGS_TO, 'AclObject', 'acl_object_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'status' => 'Status',
			'forum_id' => 'Forum',
			'acl_object_id' => 'Acl Object',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('forum_id',$this->forum_id);
		$criteria->compare('acl_object_id',$this->acl_object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}