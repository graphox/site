<?php

/**
 * This is the model class for table "entity_access".
 *
 * The followings are the available columns in table 'entity_access':
 * @property string $id
 * @property string $group_id
 * @property string $entity_id
 * @property string $entity_action_id
 * @property integer $order
 * @property integer $value
 *
 * The followings are the available model relations:
 * @property AccessGroup $group
 * @property Entity $entity
 * @property EntityTypeActions $entityAction
 */
class EntityAccess extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EntityAccess the static model class
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
		return '{{entity_access}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id, entity_id, entity_action_id, value', 'required'),
			array('order, value', 'numerical', 'integerOnly'=>true),
			array('group_id, entity_id, entity_action_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_id, entity_id, entity_action_id, order, value', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'AccessGroup', 'group_id'),
			'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
			'entityAction' => array(self::BELONGS_TO, 'EntityTypeAction', 'entity_action_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'group_id' => 'Group',
			'entity_id' => 'Entity',
			'entity_action_id' => 'Entity Action',
			'order' => 'Order',
			'value' => 'Value',
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
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('entity_id',$this->entity_id,true);
		$criteria->compare('entity_action_id',$this->entity_action_id,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
