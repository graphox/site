<?php

/**
 * This is the model class for table "acl_group".
 *
 * The followings are the available columns in table 'acl_group':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property AclGroup $parent
 * @property AclGroup[] $aclGroups
 * @property AclGroupUser[] $aclGroupUsers
 * @property AclPrivilege[] $aclPrivileges
 * @property ClanRanks[] $clanRanks
 * @property Clans[] $clans
 */
class AclGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AclGroup the static model class
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
		return 'acl_group';
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
			'parent' => array(self::BELONGS_TO, 'AclGroup', 'parent_id'),
			'aclGroups' => array(self::HAS_MANY, 'AclGroup', 'parent_id'),
			'aclGroupUsers' => array(self::HAS_MANY, 'AclGroupUser', 'group_id'),
			'aclPrivileges' => array(self::HAS_MANY, 'AclPrivilege', 'group_id'),
			'clanRanks' => array(self::HAS_MANY, 'ClanRanks', 'acl_group_id'),
			'clans' => array(self::HAS_MANY, 'Clans', 'acl_group_id'),
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