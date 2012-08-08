<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $entity_id
 * @property string $username
 * @property string $password
 * @property string $status
 * @property string $registered_date
 * @property string $activated_date
 * @property string $last_login
 *
 * The followings are the available model relations:
 * @property Email[] $emails
 * @property Sessions[] $sessions
 * @property Entity $entity
 */
class User extends CActiveRecord
{
	const STATUS_BANNED = 'banned';
	const STATUS_ACTIVE = 'active';
	const STATUS_EMAIL = 'email';
	const STATUS_ADMIN = 'admin';
	const STATUS_BOTH = 'both';

	public $_entity;
	public $access;
	
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
	 *	initializes the entity
	 */
	public function init()
	{
		parent::init();
		$this->_entity = new Entity;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('username, password, access', 'required', 'on' => 'insert,update'),
			array('username', 'length', 'max'=>45, 'on' => 'insert,update'),

			array('username, access', 'required', 'on' => 'admin'),
			array('username', 'length', 'max'=>45, 'on' => 'admin'),
			
			array('username', 'unique', 'on' => 'insert,update,admin'),
			
			array('access', 'application.components.validators.AsInArrayValidator', 'data' => $this->_entity->accessOptions, 'on' => 'insert,update,admin'),
			array('status', 'application.components.validators.AsInArrayValidator', 'data' => $this->statusOptions, 'on' => 'statusUpdate'),
			
			array('password', 'encodePassword', 'on' => 'admin'),

			array('id, entity_id, username, password, status, registered_date, activated_date, last_login', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Checks if the password field received input and encrypts it
	 */
	public function encodePassword($field)
	{
		if(isset($this->$field) && trim($this->$field) !== '')
		{
			$this->$field = Yii::app()->crypto->encodePassword($this->$field);
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{

		return array(
			'emails' => array(self::HAS_MANY, 'Email', 'user_id'),
			'sessions' => array(self::HAS_MANY, 'Sessions', 'user_id'),
			'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
		);
	}

	/**
	 * @return array an array containing the available status options
	 */
	public function getStatusOptions()
	{
		return array(
			self::STATUS_BANNED	=> Yii::t('user', self::STATUS_BANNED),
			self::STATUS_ACTIVE	=> Yii::t('user', self::STATUS_ACTIVE),
			self::STATUS_EMAIL	=> Yii::t('user', self::STATUS_EMAIL),
			self::STATUS_ADMIN	=> Yii::t('user', self::STATUS_ADMIN),
			self::STATUS_BOTH	=> Yii::t('user', self::STATUS_BOTH),
		);
	}
	
	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->_entity->type = Entity::TYPE_USER;
			if(!$this->_entity->save(false))
				throw new CException('Could not save user entity!');
			
			$this->registered_date = new CDbExpression('NOW()');
			$this->entity_id = $this->_entity->id;
		}
		
		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'entity_id' => 'Entity',
			'username' => 'Username',
			'password' => 'Password',
			'status' => 'Status',
			'registered_date' => 'Registered Date',
			'activated_date' => 'Activated Date',
			'last_login' => 'Last Login',
		);
	}
	
	/**
	 * Sends a mail to users becouse their status has changed
	 */
	public function sendStatusMail()
	{
		$mailer = Yii::app()->mailer;
		$controller = Yii::app()->controller;
		$content = '';
		$title = 'Your status has changed to: '.$this->status;
		
		switch($this->status)
		{
			#send an email to the user
			case 'banned':
				$content = $controller->renderPartial('//mail/banned', array('model' => $this), true);
				break;
			
			#send an email to the user
			case 'active':
				$content = $controller->renderPartial('//mail/active', array('model' => $this), true);
				break;
			
			#send an email to both the user and the admin
			case 'both':
				$adminContent = $controller->renderPartial('//mail/admin', array('model' => $this), true);			
				
				#mail
				
				$content = $controller->renderPartial('//mail/both', array('model' => $this), true);
			
			#send an email to the user
			case 'email':
				$content = $controller->renderPartial('//mail/email', array('model' => $this), true);

			#send an email to the user			
			case 'admin':
				$content = $controller->renderPartial('//mail/admin', array('model' => $this), true);
			
			break;
		}
		$message = new AsEmailMessage;
		$message->subject = $title;
		$message->body = $content;
		
		return $mailer->send($message);
	}
	
	/**
	 * @return Email the primary email address object of the model
	 */
	public function getPrimaryEmail()
	{
		foreach($this->emails as $email)
			if($email->is_primary === 1)
				return $email;
		
		throw new CException('User does not have a primary email!');
	}
	
	/*private $_entity_id;
	protected function beforeDelete()
	{
		$_entity_id = $this->entity_id
	}*/
	
	protected function afterDelete()
	{
		$this->entity->status = 'deleted';
		$this->entity->save();
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
		$criteria->compare('entity_id',$this->entity_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('registered_date',$this->registered_date,true);
		$criteria->compare('activated_date',$this->activated_date,true);
		$criteria->compare('last_login',$this->last_login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
