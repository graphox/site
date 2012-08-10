<?php

class BaseEntity extends CModel
{
	/**
	 * list of metadata to add to this model
	 */
	protected $metaMap = array(
	
	);

	/**
	 * List of entity fields to copy into this class
	 */
	protected $entityMap = array(
		'id',
		'type',
		'subtype_id',
		'site_id',
		'owner_id',
		'creator_id',
		'created_date',
		'updated_date',
		'access',
		'status',
	);
	
	public $id;
	public $type;
	public $subtype_id;
	public $site_id;
	public $owner_id;
	public $creator_id;
	public $created_date;
	public $updated_date;
	public $access;
	public $status;	
	
	/**
	 * the entity oject
	 */	
	protected $entity;
	
	/**
	 * is the entity a new record
	 */
	public $isNewRecord = true;
	
	/**
	 * Initializes the entity and copies the metadata and entity fields
	 */
	public function __construct($entity = null)
	{
		if($entity !== null)
		{
			$this->entity = $entity;
			$this->mapMetaData();
			$this->mapEntity();
			$this->isNewRecord = false;
		}
		
		$this->init();
		//$this->afterConstruct();
	}
	
	/**
	 * initializes the model with default values
	 * 
	 * Values intialized by default:
	 *	- site_id
	 *  - creator_id
	 *  - owner_id
	 *  - created_date
	 *  - updated date
	 */
	protected function init()
	{
		if($this->isNewRecord)
		{
			/** @todo add multisite support */
			$this->site_id = Entity::model()->findByAttributes(array('type' => 'site'))->id;

			$this->creator_id = Yii::app()->user->id;
			$this->owner_id = Yii::app()->user->id;
			$this->created_date = new CDbExpression('NOW()');
		}
		else
			$this->updated_date = new CDbExpression('NOW()');
		
	}

	/**
	 * Copies the metadata fields
	 */
	public function mapMetaData()
	{
		foreach($this->metaMap as $var)
			$this->$var = $this->entity->getMetaVar($var);
	}

	/**
	 * Copies the entity fields
	 */	
	public function mapEntity()
	{
		foreach($this->entityMap as $var)
			$this->$var = $this->entity->$var;
	}
	
	/**
	 * @return array list of validation rules
	 */
	public function rules()
	{
		return array(
		
		);
	}
	
	/**
	 * Event called after successful save
	 */
	protected function afterSave()
	{
		
	}
	/**
	 * Saves all metadata and the updated entity into the database
	 * @return bool success
	 */
	public function save($validate = true)
	{
		if($validate && !$this->validate())
			return false;
		elseif($this->beforeSave())
		{
			if($this->isNewRecord)
				$entity = new Entity;
			else
				$entity = Entity::model()->findByPk($this->entity->id);
			$transaction = $entity->getDbConnection()->beginTransaction();
			try
			{
				if(!$this->isNewRecord)
				{
					//only copy updatable fields
					foreach(
						array(
							'site_id',
							'owner_id',
							'creator_id',
							'access',
							'status'						
						)
						as $field)
						$entity->$field = $this->$field;					
				}
				else
				{
					foreach($this->entityMap as $field)
						$entity->$field = $this->$field;									
				}
				
				$entity->save(false);
				
				if(!$this->isNewRecord)
				{
					EntityMetadata::model()->deleteAllByAttributes(array(
						'entity_id' => $entity->id
					), array('in' => array('type' => $this->metaMap)));
				}
				
				foreach($this->metaMap as $meta)
				{
					$model = new EntityMetadata;
					$model->type = $meta;
					$model->entity_id = $entity->id;
					$model->value = $this->$meta;
					$model->save(false);
				}

			   $this->id = $entity->id;
			   $this->entity = $entity;
			   $transaction->commit();
			   $this->afterSave();
			}
			catch(Exception $e)
			{
			   $transaction->rollback();
			   //rethrow
			   throw $e;
			}
			return true;	
		}
		return false;
	}
	
	/**
	 * @return boolean save
	 */
	public function beforeSave()
	{
		return true;
	}
	
	/**
	 * @attribute type
	 * @return object the related entity objects
	 */
	 public function getRelations($type)
	 {
	 	$relations = array();
	 	
	 	$criteria = new CDbCriteria();
	 	$criteria->compare('entity1', $this->entity->id, false);
	 	$criteria->compare('entity2', $this->entity->id, false, 'OR');
	 	$criteria->compare('type', $type, false);
	 	
	 	$relation_models = EntityRelation::model()-> /** @todo fix this: with(array('entityA' => array('scope' => 'noscope'), 'entityB'))->*/findAll($criteria);
	 	
	 	foreach($relation_models as $relation)
		{
			//access denied or internal error
			if(!$relation->entityA || !$relation->entityB)
				continue;
			
			#we don't want to fetch the source object
	 		$relations[] = $relation->entityA->id !== $this->entity->id ?
	 			$relation->entityA->typeModel :
	 			$relation->entityB->typeModel;
		}

		return $relations;
	 }
	 
	 /**
	  * Adds a relation to a child object.
	  * @param string $type the type of the relation
	  * @param mixed $entity the entity id or object to be related to.
	  * @return boolean success
	  */
	 public function addRelation($type, $entity)
	 {
		 $model = new EntityRelation;
		 $model->entity1 = $this->id;
		 $model->entity2 = is_object($entity) ? $entity->id : $entity;
		 $model->type = $type;
		 
		 return $model->save(false);
	 }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeNames()
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
	 * Check the user for the specified access.
	 * now just returns true
	 * @attribute $action the access action to check for
	 * @return boolean If the user has the specified access on this object
	 * @todo implement
	 */
	public function can($action)
	{
		return true;
	}
}
