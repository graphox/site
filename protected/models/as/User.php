<?php

class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'users';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, pass', 'required'),
			array('priv', 'numerical', 'integerOnly'=>true),
			array('name, email, pass', 'length', 'max'=>50),
			// The following rule is used by search().
			array('id, name, email', 'safe', 'on'=>'search'),
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
			'stats' => array(self::HAS_ONE, 'Total_Stats', 'user_id'),
			'web_user' => array(self::HAS_ONE, 'Web_User', 'user_id'),
			#'priv_overrides' => array(self::HAS_MANY, 'Priv_Override', 'player_id'),
			'names' => array(self::HAS_MANY, 'Name', 'user_id'),
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
			'email' => 'Email',
			'pass' => 'Pass',
			'priv' => 'Priv',
		);
	}
}
