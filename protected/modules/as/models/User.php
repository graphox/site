<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $display_group_id
 * @property string $password
 * @property string $ingame_password
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Content[] $contents
 * @property Content[] $contents1
 * @property GroupUser[] $groupUsers
 * @property Group $displayGroup
 */
class User extends AsActiveRecord
{
	public $web_password;
	public $remember_me;
	
	/**
	 * possible values for $status
	 */
	const STATUS_BANNED = 'banned';	
	const STATUS_ACTIVE = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_OAUTH = 'oauth';
	
	/**
	 * @var user
	 * the user in the db, used in the login proccess
	 */
	private $_user;
	
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
			array('username, email, salt, ingame_password, status', 'required', 'on' => 'insert,update'),
			array('display_group_id', 'numerical', 'integerOnly'=>true, 'on' => 'insert,update'),
			array('username', 'length', 'max'=>10, 'on' => 'insert,update'),
			array('email', 'length', 'max'=>50, 'on' => 'insert,update'),
			array('status', 'validateStatus', 'on' => 'insert, update'),
			
			array('web_password', 'safe', 'on' => 'insert,update'),
			array('password', 'unsafe', 'on' => 'insert,update'),

			array('username, password', 'safe', 'on' => 'login'),
			array('username, password', 'required', 'on' => 'login'),
			
			array('password', 'validateAccount', 'on' => 'login'),
			array('status', 'validateLoginStatus', 'on' => 'login'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, display_group_id, password, salt, ingame_password', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Validate the username/email - password combination
	 */
	public function validateAccount()
	{
		if($this->hasErrors())
			return;
		
		$criteria=new CDbCriteria;
		$criteria->compare('email',$this->email);
		$criteria->compare('username', $this->username, false, 'OR');

		$user = self::model()->find($criteria);

		if($user !== null && Yii::app()->crypto->checkUserPassword($user, $this))
		{
			$this->_user = $user;
			return;
		}
		
		$this->addError('username', 'username/email or password incorrect');
	}
	
	/**
	 * Validate the status of the account on login
	 */
	public function validateLoginStatus()
	{
		if($this->hasErrors())
			return;
		
		if($this->_user->status !== self::STATUS_ACTIVE)
			$this->addError('status', 'acount status is not active');	
	}
	
	/**
	 * put the user in the session
	 */
	public function login()
	{
		$userIdentity = new AsUserIdentity($this->_user->id);
		$userIdentity->setState('email', $this->_user->email);
		$userIdentity->setState('username', $this->_user->username);
		$userIdentity->setState('is_external', $this->_user->status === self::STATUS_OAUTH);
		
		if($this->remember_me === true)
			Yii::app()->user->login($userIdentity,3600*24*7); #7 days
			
		else
			Yii::app()->user->login($userIdentity);
	}

	/**
	 * @var statusAttributes
	 * an array containing all the possible status attributtes
	 * @readonly
	 */
	public function getStatusAttributes()
	{
		return array(
		 	self::STATUS_BANNED =>	self::STATUS_BANNED,
			self::STATUS_ACTIVE	=>	self::STATUS_ACTIVE,
			self::STATUS_PENDING=>	self::STATUS_PENDING,
			self::STATUS_OAUTH	=>	self::STATUS_OAUTH,
		);
	}

	/**
	 * Validate status attribute
	 */
	public function validateStatus()
	{
		if(!isset($this->statusAttributes[$this->status]))
			$this->addError('status', 'Invalid status');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contents' => array(self::HAS_MANY, 'Content', 'updater_id'),
			'contents1' => array(self::HAS_MANY, 'Content', 'creator_id'),
			'groupUsers' => array(self::HAS_MANY, 'GroupUser', 'user_id'),
			'displayGroup' => array(self::BELONGS_TO, 'Group', 'display_group_id'),
			'groups' => array(self::MANY_MANY, 'Group', 'group_user(group_id,user_id)')
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
			'email' => 'Email',
			'display_group_id' => 'Display Group',
			'password' => 'Password',
			'ingame_password' => 'Ingame Password',
			'status' => 'User status'
		);
	}
	
	/**
	 * hashes a string to a password with salt
	 */
	public function setHashedPassword($value)
	{
		$this->password = $value;
		Yii::app()->crypto->setUserPassword($this);
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('display_group_id',$this->display_group_id);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('ingame_password',$this->ingame_password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
