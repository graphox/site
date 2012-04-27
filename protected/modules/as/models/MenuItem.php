<?php

/**
 * This is the model class for table "menu_item".
 *
 * The followings are the available columns in table 'menu_item':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 * @property integer $acl_object_id
 *
 * The followings are the available model relations:
 * @property AclObject $aclObject
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 */
class MenuItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuItem the static model class
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
		return 'menu_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, url, acl_object_id', 'required'),
			array('parent_id, acl_object_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('url', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, name, url, acl_object_id', 'safe', 'on'=>'search'),
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
			'aclObject' => array(self::BELONGS_TO, 'AclObject', 'acl_object_id'),
			'parent' => array(self::BELONGS_TO, 'MenuItem', 'parent_id'),
			'menuItems' => array(self::HAS_MANY, 'MenuItem', 'parent_id'),
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
			'url' => 'Url',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('acl_object_id',$this->acl_object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}