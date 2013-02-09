<?php

Yii::import('as.components.contentTypes.*');

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $created_date
 * @property string $updated_date
 * @property integer $creator_id
 * @property integer $updater_id
 * @property integer $acl_object_id
 * @property integer $type_id
 * @property integer $language_id
 * @property integer $can_comment
 * @property integer $markup_id
 * @property integer $published
 * @property integer $widgets_enabled
 * @property integer $parent_id
 *
 * The followings are the available model relations:
 * @property Markup $markup
 * @property User $updater
 * @property AclObject $aclObject
 * @property ContentType $type
 * @property User $creator
 * @property Language $language
 */
class Content extends AsActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Content the static model class
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
		return 'content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, content, type_id, language_id, can_comment, markup_id, published, widgets_enabled', 'required'),
			array('type_id, language_id, can_comment, markup_id, published, widgets_enabled, parent_id', 'numerical', 'integerOnly'=>true),
					array('name', 'length', 'max'=>50),
	
			array('type_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->typeOptions, 'arrayValues' => false),			
			array('language_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->languageOptions, 'arrayValues' => false),
			array('markup_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->markupOptions, 'arrayValues' => false),
			array('parent_id', 'as.components.validators.AsInArrayValidator', 'data' => $this->parentOptions, 'arrayValues' => false),

			array('name', 'unique'),
			array('name', 'cleanUrl'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, content, created_date, updated_date, creator_id, updater_id, acl_object_id, type_id, language_id, can_comment, markup_id, published, widgets_enabled, parent_id', 'safe', 'on'=>'search'),
		);
	}
	
	public function cleanUrl($field, $options)
	{
		$this->$field = preg_replace('/\W/', '-', $this->$field);
	}
	
	/**
	 * These functions return the possible values for the checkboxes
	 */
	public function getLanguageOptions()
	{
		return CHtml::listData(
			Language::model()->findAll(),
			'id', 'name'
		);
	
	}
	
	public function getTypeOptions()
	{
		return CHtml::listData(
			ContentType::model()->findAll(),
			'id', 'name'
		);
	}

	public function getMarkupOptions()
	{
		return CHtml::listData(
			Markup::model()->findAllWithAccess(array(
				'use' => false
			)),
			'id', 'name'
		);
	}
	
	public function getParentOptions()
	{
		$array = CHtml::listData(
			self::model()->findAll(),
			'id', 'name'
		);
		
		$array[null] = '= None =';
		
		return $array;
	}
	
	public $_regenerateName = false;
	
	/**
	 * Creates an acl object id and sets the updator/creator
	 */	
	public function afterValidate()
	{
		if($this->hasErrors())
			;
		elseif($this->isNewRecord)
		{
			$this->creator_id = Yii::app()->user->id;		
			$this->created_date = new CDbExpression('NOW()');
			$aclObject = Yii::app()->accessControl->addObject('content'.$this->type->name.'.temp');
			$this->acl_object_id = $aclObject->id;
			
			$this->_regenerateName = true;
		}
		else
		{
			$this->updater_id = Yii::app()->user->id;
			$this->updated_date = new CDbExpression('NOW()');
		}
	
		return parent::afterValidate();
	}
	
	/**
	 * Render the Content
	 */
	public function beforeSave()
	{
		$this->html = $this->markup->render($this->content);
		return parent::beforeSave();
	}
	
	/**
	 * Set the acl object name correct
	 */
	public function afterSave()
	{
		if($this->_regenerateName)
		{
			$aclObject = Yii::app()->accessControl->getObject($this->acl_object_id);
			$aclObject->name = 'content.'.$this->type->name.'.'.$this->id;
			
			if(!$aclObject->save(false))
				throw new CException('Could not update name!');
			
			$this->_regenerateName = false;
		}
		
		return parent::afterSave();
	}
	
	/**
	 * @return the fields to set to boolean
	 */
	public function getBooleanFields()
	{
		return array(
			'can_comment', 'published', 'widgets_enabled'
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
			'markup' => array(self::BELONGS_TO, 'Markup', 'markup_id'),
			'updater' => array(self::BELONGS_TO, 'User', 'updater_id'),
			'aclObject' => array(self::BELONGS_TO, 'AclObject', 'acl_object_id'),
			'type' => array(self::BELONGS_TO, 'ContentType', 'type_id'),
			'creator' => array(self::BELONGS_TO, 'User', 'creator_id'),
			'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
		);
	}

	/**
	 * Instantiate the type class
	 */
	protected function instantiate($attributes)
	{
		$class = ContentType::model()->findByPk($attributes['type_id'])->{"class"};

		if(strpos($class, '.') !== FALSE)
		{
			$class = explode('.', $class);

			if($class[0] === 'plugin')
			{
				unset($class[0]);
				$class = implode('.', $class);
				$plugin = Yii::app()->plugin->load($class);
				
				if($plugin !== null)
					return $plugin->instantiateContent($attributes);
			}
			else
			
			$class = Yii::import($class, true);
		}
		
		return new $class(null);

	}
	
	/**
	 * Get instance of type class
	 */
	public function getRenderClass()
	{
		return $this->instantiate($this->getAttributes(false));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'content' => 'Content',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
			'creator_id' => 'Creator',
			'updater_id' => 'Updater',
			'acl_object_id' => 'Acl Object',
			'type_id' => 'Type',
			'language_id' => 'Language',
			'can_comment' => 'Can Comment',
			'markup_id' => 'Markup',
			'published' => 'Published',
			'widgets_enabled' => 'Widgets Enabled',
			'parent_id' => 'Parent',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('creator_id',$this->creator_id);
		$criteria->compare('updater_id',$this->updater_id);
		$criteria->compare('acl_object_id',$this->acl_object_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('can_comment',$this->can_comment);
		$criteria->compare('markup_id',$this->markup_id);
		$criteria->compare('published',$this->published);
		$criteria->compare('widgets_enabled',$this->widgets_enabled);
		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
