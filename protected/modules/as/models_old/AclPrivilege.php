<?php

/**
 * This is the model class for table "acl_privilege".
 *
 * The followings are the available columns in table 'acl_privilege':
 * @property integer $id
 * @property integer $object_id
 * @property integer $group_id
 * @property integer $read
 * @property integer $write
 * @property integer $update
 * @property integer $delete
 */
class AclPrivilege extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AclPrivilege the static model class
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
		return 'acl_privilege';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object_id, group_id, read, write, update, delete', 'required'),
			array('object_id, group_id, read, write, update, delete', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, object_id, group_id, read, write, update, delete', 'safe', 'on'=>'search'),
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
			'object_id' => 'Object',
			'group_id' => 'Group',
			'read' => 'Read',
			'write' => 'Write',
			'update' => 'Update',
			'delete' => 'Delete',
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
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('read',$this->read);
		$criteria->compare('write',$this->write);
		$criteria->compare('update',$this->update);
		$criteria->compare('delete',$this->delete);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}