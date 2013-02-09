<?php

Yii::import('as.components.markupTypes.*');

/**
 * This is the model class for table "markup".
 *
 * The followings are the available columns in table 'markup':
 * @property integer $id
 * @property string $name
 * @property integer $acl_object_id
 *
 * The followings are the available model relations:
 * @property Content[] $contents
 * @property AclObject $aclObject
 */
class Markup extends AsActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Markup the static model class
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
		return 'markup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, acl_object_id, class_path', 'required'),
			array('acl_object_id', 'numerical', 'integerOnly'=>true),
			array('name, class_path', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, acl_object_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Instantiate the type class
	 */
	protected function instantiate($attributes)
	{
		$class = $attributes['class'];

		if(strpos($class, '.') !== FALSE)
		{
			$class_array = explode('.', $class);

			if($class[0] === 'plugin')
			{
				unset($class_array[0]);
				$plugin = Yii::app()->plugin->load(
					implode('.', $class_array)
				);
				
				if($plugin !== null)
					return $plugin->instantiateMarkup($attributes);
			}
			else
			
			$class = Yii::import($class, true);
		}
		
		return new $class(null);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contents' => array(self::HAS_MANY, 'Content', 'markup_id'),
			'aclObject' => array(self::BELONGS_TO, 'AclObject', 'acl_object_id'),
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
			'acl_object_id' => 'Acl Object',
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
		$criteria->compare('acl_object_id',$this->acl_object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
