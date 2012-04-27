<?php

/**
 * This is the model class for table "clans".
 *
 * The followings are the available columns in table 'clans':
 * @property integer $id
 * @property integer $name
 * @property integer $description
 * @property integer $acl_group_id
 * @property integer $status
 * @property integer $page_id
 * @property integer $forum_id
 */
class Clans extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Clans the static model class
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
		return 'clans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, acl_group_id, status', 'required'),
			array('name, description, acl_group_id, status, page_id, forum_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, acl_group_id, status, page_id, forum_id', 'safe', 'on'=>'search'),
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
			'acl_group_id' => 'Acl Group',
			'status' => 'Status',
			'page_id' => 'Page',
			'forum_id' => 'Forum',
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
		$criteria->compare('name',$this->name);
		$criteria->compare('description',$this->description);
		$criteria->compare('acl_group_id',$this->acl_group_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('forum_id',$this->forum_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}