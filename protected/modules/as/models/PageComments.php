<?php

/**
 * This is the model class for table "page_comments".
 *
 * The followings are the available columns in table 'page_comments':
 * @property integer $id
 * @property integer $user_id
 * @property integer $page_id
 * @property string $title
 * @property string $content
 * @property string $posted_date
 *
 * The followings are the available model relations:
 * @property CommentVotes[] $commentVotes
 * @property Pages $page
 * @property User $user
 */
class PageComments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PageComments the static model class
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
		return 'page_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_id, title, content', 'required'),
			array('user_id, page_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, page_id, title, content, posted_date', 'safe', 'on'=>'search'),
			array('posted_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'insert'),
			array('posted_date', 'default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false, 'on'=>'update'),
			array('user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
		);
	}
	
	public function scopes()
	{
		return array(
			'recently' => array(
				'order'=>'posted_date DESC',
                'limit'=>5,
			)
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
			'commentVotes' => array(self::HAS_MANY, 'CommentVotes', 'comment_id'),
			'page' => array(self::BELONGS_TO, 'Pages', 'page_id'),
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
			'page_id' => 'Page',
			'title' => 'Title',
			'content' => 'Content',
			'posted_date' => 'Posted Date',
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
		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('posted_date',$this->posted_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
