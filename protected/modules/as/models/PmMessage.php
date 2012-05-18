<?php

/**
 * This is the model class for table "pm_message".
 *
 * The followings are the available columns in table 'pm_message':
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property integer $read
 * @property integer $receiver_deleted
 * @property integer $receiver_dir_id
 * @property string $title
 * @property string $content
 * @property string $sended_date
 *
 * The followings are the available model relations:
 * @property User $sender
 * @property User $receiver
 * @property PmDirectory $receiverDir
 */
class PmMessage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PmMessage the static model class
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
		return 'pm_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender_id, receiver_id, read, receiver_deleted, receiver_dir_id, title, content, sended_date', 'required'),
			array('sender_id, receiver_id, read, receiver_deleted, receiver_dir_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sender_id, receiver_id, read, receiver_deleted, receiver_dir_id, title, content, sended_date', 'safe', 'on'=>'search'),
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
			'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
			'receiver' => array(self::BELONGS_TO, 'User', 'receiver_id'),
			'receiverDir' => array(self::BELONGS_TO, 'PmDirectory', 'receiver_dir_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender_id' => 'Sender',
			'receiver_id' => 'Receiver',
			'read' => 'Read',
			'receiver_deleted' => 'Receiver Deleted',
			'receiver_dir_id' => 'Receiver Dir',
			'title' => 'Title',
			'content' => 'Content',
			'sended_date' => 'Sended Date',
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
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('receiver_id',$this->receiver_id);
		$criteria->compare('read',$this->read);
		$criteria->compare('receiver_deleted',$this->receiver_deleted);
		$criteria->compare('receiver_dir_id',$this->receiver_dir_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('sended_date',$this->sended_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}