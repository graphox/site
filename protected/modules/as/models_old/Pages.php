<?php

/**
 * This is the model class for table "pages".
 *
 * The followings are the available columns in table 'pages':
 * @property integer $id
 * @property string $module
 * @property string $uri
 * @property integer $editor_id
 * @property string $content
 * @property string $change_time
 * @property integer $acl_object_id
 */
class Pages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pages the static model class
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
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module, uri, editor_id, content, change_time, acl_object_id', 'required'),
			array('editor_id, acl_object_id', 'numerical', 'integerOnly'=>true),
			array('module, uri', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, module, uri, editor_id, content, change_time, acl_object_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'module' => 'Module',
			'uri' => 'Uri',
			'editor_id' => 'Editor',
			'content' => 'Content',
			'change_time' => 'Change Time',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('editor_id',$this->editor_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('change_time',$this->change_time,true);
		$criteria->compare('acl_object_id',$this->acl_object_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}