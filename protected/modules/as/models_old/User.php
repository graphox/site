<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property integer $username
 * @property integer $ingame_password
 * @property integer $email
 * @property integer $hashing_method
 * @property integer $web_password
 * @property integer $salt
 * @property integer $status
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, ingame_password, email, hashing_method, web_password, salt, status', 'required'),
			array('username, ingame_password, email, hashing_method, web_password, salt, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, ingame_password, email, hashing_method, web_password, salt, status', 'safe', 'on'=>'search'),
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
			'names' => array(self::HAS_MANY, 'Names', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'ingame_password' => 'Ingame Password',
			'email' => 'Email',
			'hashing_method' => 'Hashing Method',
			'web_password' => 'Web Password',
			'salt' => 'Salt',
			'status' => 'Status',
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
		$criteria->compare('username',$this->username);
		$criteria->compare('ingame_password',$this->ingame_password);
		$criteria->compare('email',$this->email);
		$criteria->compare('hashing_method',$this->hashing_method);
		$criteria->compare('web_password',$this->web_password);
		$criteria->compare('salt',$this->salt);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
