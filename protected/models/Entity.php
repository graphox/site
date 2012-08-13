<?php

/**
 * This is the model class for table "entity".
 *
 * The followings are the available columns in table 'entity':
 * @property string $id
 * @property string $type
 * @property string $subtype_id
 * @property string $site_id
 * @property string $owner_id
 * @property string $creator_id
 * @property string $created_date
 * @property string $updated_date
 * @property string $access
 * @property string $status
 *
 * The followings are the available model relations:
 * @property AccessGroup[] $accessGroups
 * @property EntityType $subtype
 * @property Entity $creator
 * @property Entity[] $entities
 * @property Entity $owner
 * @property Entity[] $entities1
 * @property Entity $site
 * @property Entity[] $entities2
 * @property EntityAccess[] $entityAccesses
 * @property EntityMetadata[] $entityMetadatas
 * @property EntityRelation[] $entityRelations
 * @property EntityRelation[] $entityRelations1
 * @property User[] $users
 */
class Entity extends CActiveRecord
{
	const TYPE_SITE = 'site';
	const TYPE_GROUP = 'group';
	const TYPE_USER = 'user';
	const TYPE_OBJECT = 'object';
	
	const ACCESS_PRIVATE = 'private';
	const ACCESS_MEMBERS = 'members';
	const ACCESS_FRIENDS = 'friends';
	const ACCESS_REGISTERED = 'registered';
	const ACCESS_PUBLIC = 'public';
	const ACCESS_CUSTOM = 'custom';

	const STATUS_PUBLISHED = 'published';
	const STATUS_UNPUBLISHED = 'unpublished';
	const STATUS_DRAFT = 'draft';
	const STATUS_DELETED = 'deleted';

	/**
	 *
	 * @var boolean should we apply acl on this query
	 */
	protected $internal = false;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Entity the static model class
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
		return '{{entity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, access, status', 'required'),
			array('type', 'length', 'max'=>6),
			array('subtype_id, site_id, owner_id, creator_id, access', 'length', 'max'=>10),
			array('status', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, subtype_id, site_id, owner_id, creator_id, created_date, updated_date, access, status', 'safe', 'on'=>'search'),
		);
	}
	
	
	/**
	 * @return array valid options
	 */
	public static function getStatusOptions()
	{
		return array(
			self::STATUS_PUBLISHED	=> Yii::t('entity', self::STATUS_PUBLISHED),
			self::STATUS_UNPUBLISHED=> Yii::t('entity', self::STATUS_UNPUBLISHED),
			self::STATUS_DRAFT		=> Yii::t('entity', self::STATUS_DRAFT),
			self::STATUS_DELETED	=> Yii::t('entity', self::STATUS_DELETED),
		);
	}
	
	/**
	 * @return array valid type options
	 */
	public static function getTypeOptions()
	{
		return array(
			self::TYPE_SITE		=> Yii::t('entity', self::TYPE_SITE),
			self::TYPE_GROUP	=> Yii::t('entity', self::TYPE_GROUP),
			self::TYPE_USER		=> Yii::t('entity', self::TYPE_USER),
			self::TYPE_OBJECT	=> Yii::t('entity', self::TYPE_OBJECT),
		);
	}
	
	/**
	 * @return array valid access options
	 */
	public static function getAccessOptions()
	{
		return array(
			self::ACCESS_PRIVATE	=> Yii::t('entity', self::ACCESS_PRIVATE),
			self::ACCESS_MEMBERS	=> Yii::t('entity', self::ACCESS_MEMBERS),
			self::ACCESS_FRIENDS	=> Yii::t('entity', self::ACCESS_FRIENDS),
			self::ACCESS_REGISTERED => Yii::t('entity', self::ACCESS_REGISTERED),
			self::ACCESS_PUBLIC		=> Yii::t('entity', self::ACCESS_PUBLIC),
			self::ACCESS_CUSTOM 	=> Yii::t('entity', self::ACCESS_CUSTOM)
		);
	}
	
	/**
	 * sets the timestamps
	 * @return parent::beforesave()
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
			$this->created_date = new CDbExpression('NOW()');
		
		$this->updated_date = new CDbExpression('NOW()');
	 
		return parent::beforeSave();
	}
	
	public function defaultScope()
	{
		if($this->scenario == 'admin' or $this->scenario == 'search' or $this->internal)
				return array();
		else
			return $this->getWithAccessCondition();
	}
	
	/**
	 * Makes the request internal
	 * @return \Entity this
	 */
	public function internal()
	{
		$this->internal = true;
		return $this;
	}
	
	public function noScope()
	{
		return array();
	}
	
	public function getWithAccessCondition()
	{
		$t = $this->getTableAlias(false, false);
		if (Yii::app()->user->isGuest)
		{
			return array(
				'condition' => '
						'.$t.'.status = :status
					AND
					(
							'.$t.'.access = "public"
						OR
							(
								'.$t.'.access = "custom"
								AND (
									(
										entity_access.entity_id = '.$t.'.id
										AND entity_access.group_id = access_group.id
										AND (
											entity_access.entity_action_id = entity_type_action.id
												AND entity_type_action.entity_type_id = '.$t.'.subtype_id
												AND entity_type_action.name = "list"
										)
										AND (
											access_group.name = "world"
												OR access_group.name = "guest"
										)
										AND entity_access.value = 1
									)
									OR
									(
										NOT ( '. /*check if it wasn't just entity_access.value = 0*/ '
											entity_access.entity_id = '.$t.'.id
											AND entity_access.group_id = access_group.id
											AND (
													entity_access.entity_action_id = entity_type_action.id
													AND entity_type_action.entity_type_id = '.$t.'.subtype_id
													AND entity_type_action.name = "list"
											)
											AND (
												access_group.name = "world"
													OR access_group.name = "guest"										
											)
										)
										AND entity_type_action.entity_type_id = '.$t.'.subtype_id
										AND	entity_type_action.name = "list"
										AND	entity_type_action.default = 1
									)							
								)
							)
						)',
				'together' => true,
				'order' => 'entity_access.order',
				'with' => array(
					'entityAccess' => array(
						'alias' => 'entity_access',
						'with' => array(
							'entityAction' => array(
								'alias' => 'entity_type_action',
							),
						)						
					),
					'accessGroups' => array(
						'alias' => 'access_group',
					)
				),
				'params' => array(':status' => self::STATUS_PUBLISHED),

			);
		}
		
		#TODO: user access rule for this
		elseif(Yii::app()->user->id !== 'admin')
		{
			return array(
				'condition' => 
					$t.'.owner_id = :user_id
					OR
					'.$t.'.id = :user_id
					OR
					(
						'.$t.'.status = :status
						AND
						(
							'.$t.'.access = "public"
							OR '.$t.'.access = "registered"
							OR (
								'.$t.'.access = "friends"
								AND (
									entity_relation.type = "friend"
									AND
									(
										entity_relation.entity1 = :user_id
											OR entity_relation.entity2 = :user_id
									)
									AND
									(
										entity_relation.entity1 = '.$t.'.owner_id
											OR entity_relation.entity2 = '.$t.'.owner_id
											OR entity_relation.entity1 = '.$t.'.creator_id
											OR entity_relation.entity2 = '.$t.'.creator_id
									)
								)
							)
							OR (
								'.$t.'.access = "members"
								AND (
									entity_relation.type = "member"
									AND
									(
										entity_relation.entity1 = :user_id
											OR entity_relation.entity2 = :user_id
									)
									AND
									(
										entity_relation.entity1 = '.$t.'.owner_id
											OR entity_relation.entity2 = '.$t.'.owner_id
									)
								)
							)
							OR (
								'.$t.'.access = "custom"
									AND (
										(
											entity_access.entity_id = '.$t.'.id
												AND entity_access.group_id = access_group.id
												AND (
													entity_access.entity_action_id = entity_type_action.id
														AND entity_type_action.entity_type_id = '.$t.'.subtype_id
														AND entity_type_action.name = "list"
												)
												AND (
													access_group.name = "world"
														OR access_group.name = "member"
														OR (
															(
																access_group.entity_id = entity_relation.entity1
																	OR	access_group.entity_id = entity_relation.entity2
															)
															AND
																entity_relation.type = "member"
														)
												)
												AND
													entity_access.value = 1
										)
										OR
										(
											NOT ( '. /*check if it wasn't just entity_access.value = 0*/ '
												entity_access.entity_id = '.$t.'.id
												AND entity_access.group_id = access_group.id
												AND (
													entity_access.entity_action_id = entity_type_action.id
														AND entity_type_action.entity_type_id = '.$t.'.subtype_id
														AND entity_type_action.name = "list"
												)
												AND (
													access_group.name = "world"
														OR access_group.name = "member"
														OR (
															(
																access_group.entity_id = entity_relation.entity1
																	OR	access_group.entity_id = entity_relation.entity2
															)
															AND
																entity_relation.type = "member"
														)
												)											
											)
												AND entity_type_action.entity_type_id = '.$t.'.subtype_id
												AND	entity_type_action.name = "list"
												AND	entity_type_action.default = 1

										)
									)
								)
							)
						)',
				'together' => true,
				'params' => array(
					':user_id' => Yii::app()->user->getEntity()->id,
					':status' => self::STATUS_PUBLISHED,
				),
				'order' => 'entity_access.order',
				'with' => array(
					'entityRelations' => array(
						'alias' => 'entity_relation'
					),
					'entityAccess' => array(
						'alias' => 'entity_access',
						'with' => array(
							'entityAction' => array(
								'alias' => 'entity_type_action',
							),
						)						
					),
					'accessGroups' => array(
						'alias' => 'access_group',
					)
				),
			);
		}
		else
			return array();
	}

	/**
	 * @return array models with access
	 */

	public function findAllWithAccess()
	{
	
	}
	
	/**
	 * @attribute string the type of the object
	 * @attribute the subtype of the object
	 * @return array models of a specific type with access
	 */
	public function findAllTypeWithAccess($type = 'object', $subtype = NULL)
	{
		$criteria = new CDbCriteria($this->getWithAccessCondition());
		$criteria->compare($this->getTableAlias(false, false).'.type', $type);
		
		if($subtype !== NULL)
		{
			if(is_string($subtype))
				$subtype = EntityType::model()->findByAttributes(array('name' => $subtype))->id;
			
			$criteria->compare($this->getTableAlias(false, false).'.subtype_id', $subtype);
		}
		
		return self::model()->findAll($criteria);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'accessGroups' => array(self::HAS_MANY, 'AccessGroup', 'entity_id'),
			'subtype' => array(self::BELONGS_TO, 'EntityType', 'subtype_id'),
			'creator' => array(self::BELONGS_TO, 'Entity', 'creator_id'),
			'created' => array(self::HAS_MANY, 'Entity', 'creator_id'),
			'owner' => array(self::BELONGS_TO, 'Entity', 'owner_id'),
			'owned' => array(self::HAS_MANY, 'Entity', 'owner_id'),
			'site' => array(self::BELONGS_TO, 'Entity', 'site_id'),
			'siteOwned' => array(self::HAS_MANY, 'Entity', 'site_id'),
			'entityAccess' => array(self::HAS_MANY, 'EntityAccess', 'entity_id'),
			'metadata' => array(self::HAS_MANY, 'EntityMetadata', 'entity_id'),
			'entityRelations' => array(self::HAS_MANY, 'EntityRelation', 'entity1'),
			'entityRelations1' => array(self::HAS_MANY, 'EntityRelation', 'entity2'),
			'users' => array(self::HAS_ONE, 'User', 'entity_id'),
		);
	}

	/**
	 * This function searches for a metadata value, also searches on the parent
	 * @attribute string $var the type of the meatadata
	 * @attribute boolean $alwaysMulti always get multipue
	 * @return mixed a metadata value or an array of them
	 */
	public function getMetaVar($var, $alwaysMulti = false)
	{
		$values = array();
		
		foreach($this->metadata as $meta)
			if($meta->type == $var)
				$values[] = $meta->value;
		
		if(count($values) === 0)
			throw new CException('Could not find metadata '.CHtml::encode($var).'!');
		elseif(count($values) === 1 && $alwaysMulti === FALSE)
		{
			return end($values);
		}
		
		return $values;
	}

	/**
	 * @return object an instance of the type of this entity
	 */
	public function getTypeModel()
	{
		return Yii::createComponent($this->subtype->{'class'}, $this);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'subtype_id' => 'Subtype',
			'site_id' => 'Site',
			'owner_id' => 'Owner',
			'creator_id' => 'Creator',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
			'access' => 'Access',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('subtype_id',$this->subtype_id,true);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('creator_id',$this->creator_id,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('access',$this->access,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Changes the state to deleted, doesn't perform any non recoverable actions.
	 * @return boolean success
	 */
	public function delete()
	{
		$this->status = self::STATUS_DELETED;
		return $this->save(false);
	}
	
	/**
	 * Forces an entity to be deleted.
	 * @note: Also removes all metadata and relations (database whise).
	 * @note: It's not recoomended to use this function. use delete instead.
	 * @see Entity::delete()
	 */
	public function forceDelete()
	{
		return parent::delete();
	}
	
	public function addRelation($type, $otherId, $data = '')
	{
		$model = new EntityRelation();
		$model->entity1 = $this->id;
		$model->entity2 = $otherId;
		$model->type = $type;
		$model->data = $data;
		return $model->save(False);		
	}
	
	public function findRelation($type, $id, $id2 = null)
	{
		if($id2 === NULL)
			$id2 = $this->id;
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition('entity1', array($id, $id2));
		$criteria->addInCondition('entity2', array($id, $id2));
		$criteria->compare('type', $type);
		
		return EntityRelation::model()->find($criteria);
	}

	public function findRelations($type)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('entity1', $this->id);
		$criteria->compare('entity2', $this->id, false, 'OR');
		$criteria->compare('type', $type);		
		
		return EntityRelation::model()->findAll($criteria);
	}
}

