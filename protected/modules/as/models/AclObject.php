<?php

/**
 * This is the model class for table "acl_object".
 *
 * The followings are the available columns in table 'acl_object':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property AclObject $parent
 * @property AclObject[] $aclObjects
 * @property AclPrivilege[] $aclPrivileges
 * @property Forum[] $forums
 * @property ForumTopic[] $forumTopics
 * @property MenuItem[] $menuItems
 * @property Pages[] $pages
 */
class AclObject extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AclObject the static model class
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
		return 'acl_object';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, name', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'AclObject', 'parent_id'),
			'aclObjects' => array(self::HAS_MANY, 'AclObject', 'parent_id'),
			'aclPrivileges' => array(self::HAS_MANY, 'AclPrivilege', 'object_id'),
			'forums' => array(self::HAS_MANY, 'Forum', 'acl_object_id'),
			'forumTopics' => array(self::HAS_MANY, 'ForumTopic', 'acl_object_id'),
			'menuItems' => array(self::HAS_MANY, 'MenuItem', 'acl_object_id'),
			'pages' => array(self::HAS_MANY, 'Pages', 'acl_object_id'),
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
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}