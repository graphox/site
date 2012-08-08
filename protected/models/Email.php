<?php

/**
 * This is the model class for table "email".
 *
 * The followings are the available columns in table 'email':
 * @property string $id
 * @property string $user_id
 * @property string $email
 * @property string $key
 * @property string $status
 * @property integer $is_primary
 * @property string $registered_date
 * @property string $activated_date
 * @property string $ip
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Email extends CActiveRecord
{
	const STATUS_ACTIVE = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_BANNED = 'banned';
	const STATUS_BOTH = 'both';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Email the static model class
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
		return '{{email}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, is_primary', 'required'),
			array('is_primary', 'boolean'),
			array('email, activated_date, ip', 'length', 'max'=>45),
			array('email', 'email'),
			array('status', 'safe', 'on' => 'admin'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, email, key, status, is_primary, registered_date, activated_date, ip', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'email' => 'Email',
			'key' => 'Key',
			'status' => 'Status',
			'is_primary' => 'Is Primary',
			'registered_date' => 'Registered Date',
			'activated_date' => 'Activated Date',
			'ip' => 'Ip',
		);
	}

	/**
	 * @return array a list of status options
	 */
	public function getStatusOptions()
	{
		return array(
			self::STATUS_ACTIVE => Yii::t('email', self::STATUS_ACTIVE),
			self::STATUS_PENDING => Yii::t('email', self::STATUS_PENDING),
			self::STATUS_BANNED => Yii::t('email', self::STATUS_BANNED),
			self::STATUS_BOTH => Yii::t('email', self::STATUS_BOTH),
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('is_primary',$this->is_primary);
		$criteria->compare('registered_date',$this->registered_date,true);
		$criteria->compare('activated_date',$this->activated_date,true);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
