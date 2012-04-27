<?php

/**
 * This is the model class for table "web_pages".
 *
 * The followings are the available columns in table 'web_pages':
 * @property integer $id
 * @property string $uri
 * @property string $title
 * @property string $content
 * @property string $date
 * @property string $makeup
 * @property integer $public
 */
class Page extends CActiveRecord
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
		return 'web_pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uri, title, content, date, public', 'required'),
			array('public', 'numerical', 'integerOnly'=>true),
			array('uri', 'length', 'max'=>500),
			array('title, makeup', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uri, title, content, date, makeup, public', 'safe', 'on'=>'search'),
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
			'uri' => 'Uri',
			'title' => 'Title',
			'content' => 'Content',
			'date' => 'Date',
			'makeup' => 'Makeup',
			'public' => 'Public',
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
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('makeup',$this->makeup,true);
		$criteria->compare('public',$this->public);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
