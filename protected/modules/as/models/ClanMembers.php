<?php

/**
 * This is the model class for table "clan_members".
 *
 * The followings are the available columns in table 'clan_members':
 * @property integer $id
 * @property integer $user_id
 * @property integer $clan_id
 * @property integer $rank_id
 *
 * The followings are the available model relations:
 * @property ClanRanks $rank
 * @property User $user
 * @property Clans $clan
 */
class ClanMembers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClanMembers the static model class
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
		return 'clan_members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, clan_id, rank_id', 'required'),
			array('user_id, clan_id, rank_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, clan_id, rank_id', 'safe', 'on'=>'search'),
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
			'rank' => array(self::BELONGS_TO, 'ClanRanks', 'rank_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'clan' => array(self::BELONGS_TO, 'Clans', 'clan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'clan_id' => 'Clan',
			'rank_id' => 'Rank',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('clan_id',$this->clan_id);
		$criteria->compare('rank_id',$this->rank_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}