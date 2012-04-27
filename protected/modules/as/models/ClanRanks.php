<?php

/**
 * This is the model class for table "clan_ranks".
 *
 * The followings are the available columns in table 'clan_ranks':
 * @property integer $id
 * @property integer $name
 * @property integer $acl_group_id
 * @property integer $clan_id
 *
 * The followings are the available model relations:
 * @property ClanMembers[] $clanMembers
 * @property Clans $clan
 * @property AclGroup $aclGroup
 */
class ClanRanks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClanRanks the static model class
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
		return 'clan_ranks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, acl_group_id, clan_id', 'required'),
			array('name, acl_group_id, clan_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, acl_group_id, clan_id', 'safe', 'on'=>'search'),
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
			'clanMembers' => array(self::HAS_MANY, 'ClanMembers', 'rank_id'),
			'clan' => array(self::BELONGS_TO, 'Clans', 'clan_id'),
			'aclGroup' => array(self::BELONGS_TO, 'AclGroup', 'acl_group_id'),
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
			'acl_group_id' => 'Acl Group',
			'clan_id' => 'Clan',
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
		$criteria->compare('acl_group_id',$this->acl_group_id);
		$criteria->compare('clan_id',$this->clan_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}