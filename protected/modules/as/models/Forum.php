<?php

/**
 * This is the model class for table "forum".
 *
 * The followings are the available columns in table 'forum':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $acl_object_id
 * @property string $name
 * @property string $description
 * @property integer $main_forum
 *
 * The followings are the available model relations:
 * @property Clans[] $clans
 * @property AclObject $aclObject
 * @property Forum $parent
 * @property Forum[] $forums
 * @property ForumTopic[] $forumTopics
 */
class Forum extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Forum the static model class
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
		return 'forum';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acl_object_id, name, description, main_forum', 'required'),
			array('parent_id, acl_object_id, main_forum', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, acl_object_id, name, description, main_forum', 'safe', 'on'=>'search'),
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
			'clans' => array(self::HAS_MANY, 'Clans', 'forum_id'),
			'aclObject' => array(self::BELONGS_TO, 'AclObject', 'acl_object_id'),
			'parent' => array(self::BELONGS_TO, 'Forum', 'parent_id'),
			'forums' => array(self::HAS_MANY, 'Forum', 'parent_id'),
			'forumTopics' => array(self::HAS_MANY, 'ForumTopic', 'forum_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'acl_object_id' => 'Acl Object',
			'name' => 'Name',
			'description' => 'Description',
			'main_forum' => 'Main Forum',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('acl_object_id',$this->acl_object_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('main_forum',$this->main_forum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}