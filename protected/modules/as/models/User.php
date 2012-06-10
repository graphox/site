<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $ingame_password
 * @property string $email
 * @property string $hashing_method
 * @property string $web_password
 * @property string $salt
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property AclGroupUser[] $aclGroupUsers
 * @property ClanMembers[] $clanMembers
 * @property CommentVotes[] $commentVotes
 * @property ExternalUser[] $externalUsers
 * @property ForumMessage[] $forumMessages
 * @property Friends[] $friends
 * @property Friends[] $friends1
 * @property Images[] $images
 * @property Names[] $names
 * @property OnlinePlayer[] $onlinePlayers
 * @property PageComments[] $pageComments
 * @property Pages[] $pages
 * @property PmDirectory[] $pmDirectories
 * @property PmMessage[] $pmMessages
 * @property PmMessage[] $pmMessages1
 * @property Profile[] $profiles
 */
class User extends CActiveRecord
{
	const BANNED = 0;
	const ACTIVE = 1;
	const NOT_ACTIVATED = 2;
	
	const OAUTH_ACCOUNT = 3;
	const INACTIVE = 3;

	const STATUS_BANNED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_NOT_ACTIVATED = 2;
	
	const STATUS_OAUTH_ACCOUNT = 3;
	const STATUS_INACTIVE = 3;
	
	public $retype_password;

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
			array('status', 'numerical', 'integerOnly'=>true),
			array('username, ingame_password, email, hashing_method, web_password, salt', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, hashing_method, status', 'safe', 'on'=>'search'),
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
			'aclGroupUsers' => array(self::HAS_MANY, 'AclGroupUser', 'user_id'),
			'aclGroups'=>array(self::MANY_MANY, 'AclGroup', 'acl_group_user(user_id, group_id)'),		
			'clanMembers' => array(self::HAS_MANY, 'ClanMembers', 'user_id'),
			'commentVotes' => array(self::HAS_MANY, 'CommentVotes', 'user_id'),
			'externalUsers' => array(self::HAS_MANY, 'ExternalUser', 'user_id'),
			'forumMessages' => array(self::HAS_MANY, 'ForumMessage', 'user_id'),
			'people_that_have_you_as_friends' => array(self::HAS_MANY, 'Friends', 'friend_id'),
			'friends' => array(self::HAS_MANY, 'Friends', 'owner_id'),
			'images' => array(self::HAS_MANY, 'Images', 'owned_by'),
			'names' => array(self::HAS_MANY, 'Names', 'user_id'),
			'onlinePlayers' => array(self::HAS_MANY, 'OnlinePlayer', 'user_id'),
			'pageComments' => array(self::HAS_MANY, 'PageComments', 'user_id'),
			'pages' => array(self::HAS_MANY, 'Pages', 'editor_id'),
			'pmDirectories' => array(self::HAS_MANY, 'PmDirectory', 'user_id'),
			'pmMessages' => array(self::HAS_MANY, 'PmMessage', 'sender_id'),
			'pmMessages1' => array(self::HAS_MANY, 'PmMessage', 'receiver_id'),
			'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
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
	
	public function primaryKey()
	{
		return 'id';
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username);
		$criteria->compare('email',$this->email);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	/*
	public function behaviors()
	{
		return array(
			"statusName" => array(
				"class" => "as.components.StateMachine",
				"states" => array(
					array(
						"class" => "UserPendingState",
						"name" => "pending",
					),
					array(
						"class" => "UserActiveState",
						"name" => "active",
					),
					/ * //TODO: fix double include -> class already defined
					array(
						"class" => "UserInActiveState",
						"name" => "inactive",
					),
					
					array(
						"class" => "UserBannedState",
						"name" => "banned",
					),* /
					
					array(
						"class" => "UserOauthState",
						"name" => "oauth_user",
					),
				),
				"defaultStateName" => "pending",
				"stateName" => null#UserStatus::getName($this->status),
			)
		);
	}*/

}
