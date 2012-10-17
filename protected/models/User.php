<?php

/**
 * This is the model class for "User" nodes.
 *
 * The followings are the available columns in table 'user':
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 *
 * @property int $lastLoggedIn
 * @property string $lastLoggedInHost
 * 
 * @property bool $isEmailActivated
 * @property string $emailActivationKey
 * 
 * @property bool $isAdminActivated
 *
 * @property bool $isBanned
 * @property string $bannedReason the reason why the user is banned
 * 
 * @property string $registeredHost
 */
class User extends ENeo4jNode implements ICommentable
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

	public function properties()
    {
        return CMap::mergeArray(parent::properties(),array(
            'username'			=>	array('type' => 'string'),
            'password'			=>	array('type' => 'string'),
            'email'				=>	array('type' => 'string'),
			
			'lastLoggedIn'		=>	array('type' => 'integer'),
			'lastLoggedInHost'	=>	array('type' => 'string'),
			
			'isEmailActivated'	=>	array('type' => 'boolean'),
			'emailActivationKey'=>	array('type' => 'string'),
			
			'isAdminActivated'	=>	array('type' => 'boolean'),
					
			'isBanned'			=>	array('type' => 'boolean'),
			'bannedReason'		=>	array('type' => 'string'),
			
			'registeredHost'	=>	array('type' => 'string'),
			'registeredDate'	=>	array('type' => 'integer'),
			
			//Profile
			'content'			=>	array('type' => 'string'),
			'source'			=>	array('type' => 'string'),
			
			'firstName'			=>	array('type' => 'string'),
			'lastName'			=>	array('type' => 'string'),
			'publicEmail'		=>	array('type' => 'boolean'),
			'publicName'		=>	array('type' => 'boolean'),
			
			'country'			=>	array('type' => 'string'),
			'city'				=>	array('type' => 'string'),
			
			'homepage'			=>	array('type' => 'string[]'),
			
			'canComment'		=>	array('type' => 'boolean'),
			
			//should be removed in the future and replaced by rbacl
			'isAdmin'			=>	array('type' => 'boolean'),
        ));
    }
	
	public function behaviors()
	{
		return array(
			'Commentable' => array(
				'class' => 'application.components.Commentable'
			)
		);
	}
	
	/**
	 * Makes a friend request to an user.
	 * @todo add a message to the user.
	 * @param User $friend
	 * @return bool whether the relation was successfully added.
	 */
	public function makeFriendRequest(User $friend)
	{
		return $this->addRelationshipTo($friend, '_FRIEND_');
	}
	
	/**
	 * Add an user as friend.
	 * @param User $friend
	 * @return boolean.
	 */
	public function addFriend(User $friend)
	{
		return $friend->addRelationshipTo($this, '_FRIEND_')
				&& $this->addRelationshipTo($friend, '_FRIEND_');
	}


	/**
	 * Updates the last time logged in
	 * @param bool $save call ->save() after setting the time.
	 */
	public function updateLastLoggedIn($save = true)
	{
		$this->lastLoggedIn = time();
		$this->lastLoggedInHost = Yii::app()->user->hostName;
		
		if($save)
			$this->update();
	}

	/**
	 * Sends an email when the email is activated.
	 */
	public function actionEmailActivated()
	{
		if($this->sendStatusMail('Successfully activated your email address.', '//mail/email'))
		{
			$this->isEmailActivated = true;
			$this->emailActivationKey = null;
			if($this->update())
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Sends an email after banning the user.
	 * @param string $reason the reason to ban the user, optional
	 * @return whether the email was successfully sent and the user was banned
	 */
	public function actionBan($reason = 'No reason was provided.')
	{
		if($this->sendStatusMail('You were banned.', '//mail/banned', array('reason' => $reason)))
		{
			$this->isBanned = true;
			$this->bannedReason = $reason;
			if($this->update())
				return true;
		}
		
		return false;
	}
	
	/**
	 * Send an email after marking the user as admin activated.
	 * @return boolean whether the user was successfully activated.
	 */
	public function actionAdminActivate()
	{
		if($this->sendStatusMail('An admin finally activated your account.', '//mail/admin'))
		{
			$this->isAdminActivated = true;
			if($this->update())
				return true;
		}
		return false;
	}
	
	/**
	 * Sends an email with the activation key after the user is marked as activated.
	 * @return boolean whether the email was successfully saved and the user saved.
	 */
	public function actionRegistered()
	{
		$this->emailActivationKey = Yii::app()->crypto->generateRandomKey();
		if($this->sendStatusMail('Successfully registered, please activate your account.', '//mail/registered') && $this->update())
			return true;
		
		return false;
	}
	
	/**
	 * @return boolean whether the user is banned.
	 */
	public function isBanned()
	{
		return $this->isBanned;
	}
	
	/**
	 * @return boolean whether the user has activated it's email address.
	 */
	public function isEmailActivated()
	{
		return $this->isEmailActivated;
	}
	
	/**
	 * @return boolean whether the user has been activated by an admin.
	 */
	
	public function isAdminActivated()
	{
		return $this->isAdminActivated;
	}
	
	/**
	 * @return boolean whether the user is able to login.
	 */
	public function canLogin()
	{
		return $this->isAdminActivated && $this->isEmailActivated;
	}
		
	private function sendStatusMail($title, $partial, $attr = array())
	{
		$message = new AsEmailMessage;
		$message->subject = $title;
		$message->html_body = Yii::app()->controller->renderPartial(
			$partial,
			CMap::mergeArray(
				array(
					'model' => $this
				),
				$attr
			),
			true
		);
		$message->body = strip_tags($message->html_body);
		$message->to = $this->email;
		
		return Yii::app()->mailer->send($message);
	}
	
	public function traversals()
	{
		return array(
			'friends'			=>	array(self::HAS_MANY,self::NODE,'out("_FRIEND_")'),
            'friendsOfFriends'	=>	array(self::HAS_MANY,self::NODE,'out("_FRIEND_").out("_FRIEND_")'),
        );
	}
	
	/**
	 * @todo only allow alphanumeric name
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			//Basic rules
			array('username, password', 'required', 'on' => 'insert,update'),
			array('username', 'length', 'max'=>45, 'on' => 'insert,update'),

			array('username', 'ENeo4jValidatorUnique', 'on' => 'insert,update,admin'),
			
			//admin 
			array('username', 'required', 'on' => 'admin'),
			array('username', 'length', 'max'=>45, 'on' => 'admin'),
			array('email', 'email', 'on' => 'admin'),
						
			array('isAdmin', 'in', 'range' => array(0, 1), 'on' => 'admin'),
			
			//Profile Rules
			array('source, firstName, lastName, publicEmail, publicName, country, city, homepage, canComment', 'safe', 'on' => 'profile'),
			array('canComment, publicName, publicEmail', 'in', 'range' => array(0, 1), 'on' => 'profile')
		);
	}
	
	/**
	 * Encodes the password
	 */
	public function encodePassword()
	{
		$this->password = Yii::app()->crypto->encodePassword($this->password);
	}
	
	/**
	 * @todo Find nicer way of encoding password on set.
	 * @param type $values
	 * @param type $safeOnly
	 * @param type $checkPassword
	 */
	public function setAttributes($values, $safeOnly = true, $checkPassword = true)
	{
		parent::setAttributes($values, $safeOnly);
		
		if($checkPassword && isset($values['password']) && $this->scenario !== 'admin' && $this->scenario !== 'profile')
		{
			$this->encodePassword();
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
	
	protected function afterValidate()
	{
		if(!empty($this->source))
			$this->content = Yii::app()->contentMarkup->safeTransform($this->source);
		parent::afterValidate();
	}
	
	public function beforeSave()
	{
		if($this->isNewResource)
		{
			$this->registeredDate = time();
			$this->registeredHost = Yii::app()->user->hostName;
		}
		
		return parent::beforeSave();
	}

	public function init()
	{
		parent::init();
		/** makes shure all forms like RegisterForm still keep the correct class */
		$modelclassfield=$this->getModelClassField();
		$this->$modelclassfield = __CLASS__;
	}
	
	public function getDisplayName()
	{
		return $this->publicName ? $this->firstName.' '.$this->lastName.'('.$this->username.')' : $this->username;
	}
	
	public function delete()
	{
		throw new CException('Not implemented!');
	}
	
	/**
	 * @todo: use role based permissions
	 */
	
	public function isAdmin()
	{
		return $this->isAdmin;
	}
	
	public function hasAccess($node)
	{
		return $this->isAdmin();
	}
	
	public function getBadges()
	{
		return array(
			(object)array('type' => 'info', 'label' => 'cool!')
		);
	}
}
