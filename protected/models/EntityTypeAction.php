<?php

/**
 * This is the model class for table "entity_type_action".
 *
 * The followings are the available columns in table 'entity_type_action':
 * @property string $id
 * @property string $entity_type_id
 * @property string $name
 * @property integer $default
 *
 * The followings are the available model relations:
 * @property EntityAccess[] $entityAccesses
 * @property EntityType $entityType
 */
class EntityTypeAction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EntityTypeActions the static model class
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
		return '{{entity_type_action}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('default', 'numerical', 'integerOnly'=>true),
			array('entity_type_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, entity_type_id, name, default', 'safe', 'on'=>'search'),
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
			'entityAccess' => array(self::HAS_MANY, 'EntityAccess', 'entity_action_id'),
			'entityType' => array(self::BELONGS_TO, 'EntityType', 'entity_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'entity_type_id' => 'Entity Type',
			'name' => 'Name',
			'default' => 'Default',
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
		$criteria->compare('entity_type_id',$this->entity_type_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('default',$this->default);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
