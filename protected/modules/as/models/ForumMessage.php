<?php

/**
 * This is the model class for table "forum_message".
 *
 * The followings are the available columns in table 'forum_message':
 * @property integer $id
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $title
 * @property string $content
 * @property string $date_added
 * @property string $date_changed
 *
 * The followings are the available model relations:
 * @property ForumTopic $topic
 * @property User $user
 */
class ForumMessage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ForumMessage the static model class
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
		return 'forum_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, topic_id, title, content, date_added, date_changed', 'required'),
			array('user_id, topic_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, topic_id, title, content, date_added, date_changed', 'safe', 'on'=>'search'),
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
			'topic' => array(self::BELONGS_TO, 'ForumTopic', 'topic_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'topic_id' => 'Topic',
			'title' => 'Title',
			'content' => 'Content',
			'date_added' => 'Date Added',
			'date_changed' => 'Date Changed',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('topic_id',$this->topic_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('date_changed',$this->date_changed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}